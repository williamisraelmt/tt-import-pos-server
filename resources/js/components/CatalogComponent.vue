<template>
    <div>
        <shop-header-component></shop-header-component>
        <div class="navbar-expand-md">
            <div id="navbar-menu">
                <div class="navbar navbar-transparent">
                    <div class="container-xl">
                        <div class="input-group w-100">
                            <input type="text" class="form-control" placeholder="Search…"
                                   aria-label="Search in website" v-model="search">
                            <button class="btn btn-primary input-group-append" v-on:click="paginate(1)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24"
                                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <circle cx="10" cy="10" r="7"></circle>
                                    <line x1="21" y1="21" x2="15" y2="15"></line>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content pt-0">
            <div class="page-wrapper">
                <div class="page-body mt-0">

                    <div class="container-xl">
                        <div class="row">
                            <!--                            <div class="col-3">-->
                            <!--                                <form action="" method="get">-->
                            <!--                                    <div class="subheader mb-2">Marcas</div>-->
                            <!--                                    <div class="mb-3">-->
                            <!--                                        <label class="form-check mb-1">-->
                            <!--                                            <input type="checkbox" class="form-check-input" name="form-tags[]"-->
                            <!--                                                   value="business" checked="">-->
                            <!--                                            <span class="form-check-label">VINI</span>-->
                            <!--                                        </label>-->
                            <!--                                        <label class="form-check mb-1">-->
                            <!--                                            <input type="checkbox" class="form-check-input" name="form-tags[]"-->
                            <!--                                                   value="evening">-->
                            <!--                                            <span class="form-check-label">TG77</span>-->
                            <!--                                        </label>-->
                            <!--                                    </div>-->

                            <!--                                    <div class="subheader mb-2">Categorías</div>-->
                            <!--                                    <div>-->
                            <!--                                        <label class="form-check mb-1">-->
                            <!--                                            <input type="checkbox" class="form-check-input" name="form-tags[]"-->
                            <!--                                                   value="business" checked="">-->
                            <!--                                            <span class="form-check-label">CAT 1</span>-->
                            <!--                                        </label>-->
                            <!--                                        <label class="form-check mb-1">-->
                            <!--                                            <input type="checkbox" class="form-check-input" name="form-tags[]"-->
                            <!--                                                   value="evening">-->
                            <!--                                            <span class="form-check-label">CAT 2</span>-->
                            <!--                                        </label>-->
                            <!--                                    </div>-->

                            <!--                                </form>-->
                            <!--                            </div>-->
                            <div class="col-12" v-if="!loading && total">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="page-header d-print-none mb-1 mt-0">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <!--                                                    <h2 class="page-title">-->
                                                    <!--                                                        Search results-->
                                                    <!--                                                    </h2>-->
                                                    <div class="text-muted mt-1">Mostrando
                                                        <b>{{ total > grid.limit ? grid.limit : total }}</b> de
                                                        <b>{{ total }}</b> artículos, página <b>{{ currentPage }}</b> de <b>{{ pageTotal }}</b>.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12" v-for="product in products">
                                        <div class="card mb-3">
                                            <div class="d-flex flex-row">
                                                <div>
                                                    <a href="#" v-if="product.defaultPhotoUrl">
                                                        <img class="p-3 pr-0"
                                                             style="height:150px"
                                                             v-bind:src="`/api/catalog/photo/${product.id}/${product.defaultPhotoUrl}`"
                                                        ></a>
                                                </div>
                                                <div>
                                                    <div class="card-body">
                                                        <h3 class="card-title">
                                                            <router-link
                                                                v-bind:to="`/catalog/detail?product_id=${product.id}&search=${search}`">
                                                                {{ product.name }}
                                                            </router-link>
                                                        </h3>
                                                        <ul class="text-muted small lh-base list-unstyled">
                                                            <li>Referencia: {{ product.defaultCode }}</li>
                                                            <li>Marca: {{ product.brand }}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="d-none d-md-flex">
                                        <div class="text-muted d-flex align-items-center">
                                            Ver
                                            <div class="mx-2">
                                                <input type="text" class="form-control" value="8" size="3"
                                                       v-model="grid.limit">
                                            </div>
                                            artículos
                                        </div>
                                        <div class="m-0 justify-content-md-end flex-grow-1 pagination m-0 ml-auto">
                                            <paginate
                                                v-model="currentPage"
                                                :page-count="pageTotal"
                                                :prev-link-class="'page-link pagination-link--active'"
                                                :next-link-class="'page-link pagination-link--active'"
                                                :page-link-class="'page-link pagination-link--active'"
                                                :page-range="grid.limit"
                                                :click-handler="paginate"
                                                :prev-text="'Anterior'"
                                                :next-text="'Siguiente'"
                                                :container-class="'pagination m-0 ml-auto'"
                                                :page-class="'page-item'">
                                            </paginate>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="d-flex d-md-none">
                                        <div class="flex-grow-1" style="margin-right: .25rem!important">
                                            <button class="btn btn-white w-100" v-if="currentPage !== 1"
                                                    v-on:click="paginate(currentPage - 1)">Atrás
                                            </button>
                                            <button class="btn btn-ghost-dark disabled w-100" v-else>
                                                Atrás
                                            </button>
                                        </div>
                                        <div class="flex-grow-1">
                                            <button class="btn btn-info w-100"
                                                    v-if="currentPage !== pageTotal"
                                                    v-on:click="paginate(currentPage + 1)">Siguiente
                                            </button>
                                            <button class="btn btn-ghost-info disabled w-100" v-else>
                                                Siguiente
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 d-flex flex-column justify-content-center" v-if="!loading && !total">
                                <div class="empty">
                                    <div class="empty-img">
                                        <img
                                            src="https://preview.tabler.io/static/illustrations/undraw_printing_invoices_5r4r.svg"
                                            height="128" alt="">
                                    </div>
                                    <p class="empty-title">Sin resultados</p>
                                </div>
                            </div>
                            <div class="col-12 align-items-center" v-if="loading">
                                Cargando...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import {debounce, isNull} from "lodash";
import {Grid} from "../models/Grid";
import {Product} from "../models/Product";
import {CONSTS} from "../consts";
import ShopHeaderComponent from "./ShopHeaderComponent";
import {parseCatalogRouteParameters} from '../helpers/parse-catalog-route-parameters';

const ENDPOINT = CONSTS.HOST + 'catalog';

export default {
    components: {ShopHeaderComponent},
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
            grid: {
                limit: 10,
                sortBy: [],
            },
            loading: true,
            errored: false,

            search: "",

            pageTotal: 0,
            total: 0,
            currentPage: null

        }
    },
    watch: {
        grid: {
            handler: debounce(function (val) {
                this.getCatalog();
            }, 800),
            deep: true
        },
        $route: {
            handler: function (to, from) {
                this.loadCatalogUsingRouteParameters()
            },
            deep: true
        }
    },
    methods: {
        paginate: function (val) {
            this.$router.push({path: '/catalog', query: {search: this.search, currentPage: val.toString()}})
        },
        loadCatalogUsingRouteParameters: function () {
            const params = parseCatalogRouteParameters(this.$route.query);
            this.search = params.search;
            if (this.currentPage !== params.currentPage) {
                this.currentPage = params.currentPage;
            }
            this.getCatalog();
        },
        getCatalog: function () {
            this.loading = true;
            const grid = new Grid(
                this.grid.limit,
                this.grid.limit * (this.currentPage - 1),
                this.grid.sortBy,
                this.search
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
                            product.brand
                        )
                    );
                    this.total = response.data.total;
                    this.pageTotal = Math.ceil((this.total / grid.limit) || 0);
                })
                .catch(error => {
                    this.errored = true
                })
                .finally(() => this.loading = false)
        }
    },
    mounted() {
        this.loadCatalogUsingRouteParameters();
    },
}
</script>

<style scoped>

</style>
