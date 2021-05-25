<template>
    <div class="page-wrapper">
        <div class="page-body">
            <div class="container-xl">
                <!-- Page title -->
                <div class="row row-deck row-cards">
                    <div class="col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Productos</h3>
                            </div>
                            <div class="card-body border-bottom pb-0">
                                <div class="row row-sm">
                                    <div class="col-sm-12 col-md-auto flex-md-grow-1 mb-3">
                                        <input type="text" class="form-control" placeholder="Buscar"
                                               v-model="grid.search">
                                    </div>
                                    <div class="col-auto mb-3">
                                        <odoo-sync-button-component v-on:synced="getProducts()"></odoo-sync-button-component>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mb-0">
                                <table class="table card-table table-vcenter text-nowrap datatable  table-striped">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th class="w-1">Id</th>
                                        <th>Nombre</th>
                                        <th>Cantidad disp.</th>
                                        <th>Código</th>
                                        <th>Marca</th>
                                        <th>Categoría</th>
                                        <th class="text-center">Costo</th>
                                        <th class="text-center">Precio</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-if="!loading" v-for="product in products">
                                        <td>
                                            <v-popover
                                                trigger="hover"
                                                :placement="'bottom-start'"
                                                :auto-hide="true"
                                                v-if="product.defaultPhotoUrl"
                                                class=" cursor-pointer">
                                                <!-- This will be the popover target (for the events and position) -->
                                                <span class="avatar avatar-sm tooltip-target b3"
                                                      v-bind:style="{ backgroundImage: `url(/api/product/photo/${product.id}/${product.defaultPhotoUrl})` }">
                                        </span>

                                                <!-- This will be the content of the popover -->
                                                <template slot="popover">
                                                    <img class="tooltip-content"
                                                         v-bind:src="`/api/product/photo/${product.id}/${product.defaultPhotoUrl}`"
                                                         alt="">
                                                </template>
                                            </v-popover>


                                            <span v-if="!product.defaultPhotoUrl" class="avatar avatar-sm"></span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ product.id }}</span>
                                        </td>
                                        <td class="font-weight-bold">
                                            {{ product.name }}
                                        </td>
                                        <td class="text-center">
                                            {{ product.availableQuantity }}
                                        </td>
                                        <td>
                                            {{ product.defaultCode }}
                                        </td>
                                        <td>
                                            {{ product.brand }}
                                        </td>
                                        <td>
                                            {{ product.category }}
                                        </td>
                                        <td style="text-align: right">
                                            {{ product.standardPrice | currency }}
                                        </td>
                                        <td style="text-align: right">
                                            {{ product.listPrice | currency }}
                                        </td>
                                        <td>
                                            <template v-if="product.status">
                                                <span class="badge bg-success me-1" style="vertical-align: middle"></span> Activo
                                            </template>
                                            <template v-if="!product.status">
                                                <span class="badge bg-warning me-1" style="vertical-align: middle"></span>Inactivo
                                            </template>
                                        </td>
                                        <td>

                                    <span class="dropdown ml-1">
                                        <button class="btn btn-white btn-sm dropdown-toggle align-text-top"
                                                data-boundary="viewport" data-toggle="dropdown">Acciones</button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                          <a class="dropdown-item" href="#" @click="editProduct()">
                                            Editar
                                          </a>
                                          <a class="dropdown-item" href="#" @click="uploadPhotos(product)">
                                            Cargar / Eliminar fotos
                                          </a>
                                        </div>
                                  </span>
                                        </td>
                                    </tr>
                                    <tr v-if="!loading && !products.length">
                                        <td class="text-center" colspan="9">Sin resultados.</td>
                                    </tr>
                                    <tr v-if="loading">
                                        <td colspan="9">Cargando...</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer d-flex align-items-center" v-if="!loading">
                                <div class="text-muted">
                                    Ver
                                    <div class="mx-2 d-inline-block">
                                        <input type="text" class="form-control" value="8" size="3"
                                               v-model="grid.limit">
                                    </div>
                                    entradas
                                </div>
                                <v-pagination class="m-0 ml-auto" v-model="currentPage"
                                              :classes="bootstrapPaginationClasses"
                                              :page-count="pageTotal"></v-pagination>
                            </div>
                        </div>
                    </div>
                </div>

                <vue-confirm-dialog></vue-confirm-dialog>

            </div>
        </div>
    </div>
</template>

<script>
import {debounce} from 'lodash';
import {CONSTS} from '../consts';
import {Grid} from "../models/Grid";
import {Product} from "../models/Product";
import ProductPhotoModalComponent from "./ProductPhotoModalComponent";


const ENDPOINT = CONSTS.HOST + 'product';
// const UPDATE_STATUS_ENDPOINT = ENDPOINT + '/status';

export default {
    data: function () {
        return {
            bootstrapPaginationClasses: {
                ul: 'pagination m-0 ml-auto',
                li: 'page-item',
                liActive: 'active',
                liDisable: 'disabled',
                button: 'page-link'
            },
            products: [],
            pageTotal: 0,
            total: 0,
            currentPage: 1,
            grid: {
                limit: 10,
                sortBy: [],
                search: "",
            },
            loading: true,
            errored: false
        }
    },
    watch: {
        grid: {
            handler: debounce(function (val) {
                this.getProducts();
            }, 800),
            deep: true
        },
        currentPage: {
            handler: function (val) {
                this.getProducts();
            },
            deep: true
        },
    },
    methods: {
        uploadPhotos: function (selectedProduct) {
            this.$modal.show(ProductPhotoModalComponent, {
                selectedProduct
            }, {
                width: '60%',
                height: 'auto',
                clickToClose: false
            }, {
                'closed': () => {
                    this.getProducts();
                }
            })
        },
        getProducts: function () {
            this.loading = true;
            const grid = new Grid(
                this.grid.limit,
                this.grid.limit * (this.currentPage - 1),
                this.grid.sortBy,
                this.grid.search
            );
            axios
                .get(ENDPOINT + grid.toHttpQueryString())
                .then(response => {
                    this.products = response.data.data.map(
                        (product) => new Product(
                            product.id,
                            product.name,
                            product.defaultCode,
                            product.status,
                            product.category,
                            product.availableQuantity,
                            product.defaultPhotoUrl,
                            product.brand,
                            product.listPrice,
                            product.standardPrice
                        )
                    );
                    this.total = response.data.total;
                    this.pageTotal = Math.ceil((this.total / grid.limit) || 0);
                })
                .catch(error => {
                    console.log(error);
                    this.errored = true
                })
                .finally(() => this.loading = false)
        },
        editProduct: function(productId) {

        },
        // updateStatus: function (productId, status) {
        //     axios
        //         .put(`${UPDATE_STATUS_ENDPOINT}/${productId}`, {
        //             status: status
        //         })
        //         .then(response => {
        //             this.$confirm(
        //                 {
        //                     title: 'Información',
        //                     message: 'El producto ha sido actualizado!',
        //                     button: {
        //                         yes: 'OK',
        //                     }
        //                 });
        //         })
        //         .catch(error => {
        //             this.$confirm(
        //                 {
        //                     title: 'Información',
        //                     message: 'Hubo un error al intentar actualizar el producto!',
        //                     button: {
        //                         yes: 'OK',
        //                     }
        //                 });
        //             console.log(error);
        //             this.errored = true
        //         })
        //         .finally(() => {
        //             this.getProducts();
        //         })
        // }
    },
    mounted() {
        this.getProducts();
    },
}
</script>
