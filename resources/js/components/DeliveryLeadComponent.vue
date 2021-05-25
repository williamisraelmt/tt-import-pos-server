<template>
    <div class="page-wrapper">
        <div class="page-body">
            <div class="container-xl">
                <!-- Page title -->
                <div class="row row-deck row-cards">
                    <div class="col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Conduces</h3>
                            </div>
                            <div class="card-body border-bottom pb-0">
                                <div class="row row-sm">
                                    <div class="col-sm-12 col-md-auto flex-md-grow-1 mb-3">
                                        <input type="text" class="form-control" placeholder="Buscar"
                                               v-model="grid.search">
                                    </div>
                                    <div class="col-auto mb-3">
                                        <button class="btn btn-primary ml-1" @click="createLeads()">Crear conduces
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mb-0">
                                <table class="table card-table table-vcenter text-nowrap datatable">
                                    <thead>
                                    <tr>
                                        <th class="w-1">Id</th>
                                        <th>Cliente</th>
                                        <th>Facturas afectadas</th>
                                        <th>Bultos</th>
                                        <th>Fecha</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-if="!loading" v-for="(deliveryLead,index) in deliveryLeads">
                                        <td>
                                            <span class="text-muted">{{ deliveryLead.id }}</span>
                                        </td>
                                        <td class="font-weight-bold">
                                            {{ deliveryLead.customerName }}
                                        </td>
                                        <td>
                                            <span class="badge bg-blue-lt m-1" v-for="invoice in deliveryLead.invoices">
                                             {{ invoice }}
                                            </span>
                                        </td>
                                        <td class="font-weight-bold">
                                            {{ deliveryLead.packageQuantity }}
                                        </td>
                                        <td>
                                            {{ deliveryLead.createdAt }}
                                        </td>
                                        <td>

                                    <span class="dropdown ml-1">
                                        <button class="btn btn-white btn-sm dropdown-toggle align-text-top"
                                                data-boundary="viewport" data-toggle="dropdown">Acciones</button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                          <a class="dropdown-item" href="#" @click="printDeliveryLead(deliveryLead)">
                                            Imprimir
                                          </a>
                                          <a class="dropdown-item" href="javascript:void(0)"
                                             @click="deleteDeliveryLead(deliveryLead.id)">
                                            Borrar
                                          </a>
                                        </div>
                                  </span>
                                        </td>
                                    </tr>
                                    <tr v-if="!loading && !deliveryLeads.length">
                                        <td class="text-center" colspan="6">Sin resultados.</td>
                                    </tr>
                                    <tr v-if="loading">
                                        <td colspan="6">Cargando...</td>
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
import {DeliveryLead} from "../models/DeliveryLead";
import CreateLeadModalComponent from "./CreateLeadModalComponent";

const ENDPOINT = CONSTS.HOST + 'delivery-lead';

export default {
    data: function () {
        return {
            selectedDeliveryLeads: [],
            bootstrapPaginationClasses: {
                ul: 'pagination m-0 ml-auto',
                li: 'page-item',
                liActive: 'active',
                liDisable: 'disabled',
                button: 'page-link'
            },
            deliveryLeads: [],
            pageTotal: 0,
            total: 0,
            currentPage: 1,
            grid: {
                limit: 10,
                sortBy: [],
                search: "",
            },
            loading: true,
            errored: false,
        }
    },
    watch: {
        grid: {
            handler: debounce(function (val) {
                this.getDeliveryLeads();
            }, 800),
            deep: true
        },
        currentPage: {
            handler: function (val) {
                this.getDeliveryLeads();
            },
            deep: true
        },
    },
    methods: {
        removeSelectedDeliveryLead: function (index) {
            this.selectedDeliveryLeads.splice(index, 1);
        },
        getDeliveryLeads: function () {
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
                    this.deliveryLeads = response.data.data.map(
                        (deliveryLead) => new DeliveryLead(
                            deliveryLead.id,
                            deliveryLead.customerName,
                            deliveryLead.invoices,
                            deliveryLead.packageQuantity,
                            deliveryLead.createdAt
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
        printLeads: function (deliveryLeads) {
            let queryString = '';
            deliveryLeads
                .map(deliveryLead => deliveryLead.id)
                .forEach((leadId, i) => {
                    queryString += `lead_id[]=${leadId}` + (i === (deliveryLeads.length - 1) ? '' : '&');
                });
            let a = document.createElement('a');
            a.target = "_blank";
            a.href = '/entities/delivery-lead/print?' + queryString;
            a.click();
        },
        printDeliveryLead: function (deliveryLead) {
            this.printLeads([deliveryLead]);
        },
        printBulkDeliveryLead: function () {
            this.printLeads(this.selectedDeliveryLeads);
        },
        createLeads: function () {
            this.$modal.show(CreateLeadModalComponent, {
                selectedCustomers: []
            }, {
                width: '60%',
                height: 'auto'
            })
        },
        deleteDeliveryLead: function (deliveryLeadId) {
            this.$confirm(
                {
                    message: `Está seguro que desea eliminar este conduce?`,
                    button: {
                        no: 'No',
                        yes: 'Sí'
                    },
                    /**
                     * Callback Function
                     * @param {Boolean} confirm
                     */
                    callback: confirm => {
                        if (confirm) {
                            axios.delete(`${ENDPOINT}/${deliveryLeadId}`, {})
                                .then(response => {
                                    this.getDeliveryLeads();
                                    this.$confirm(
                                        {
                                            title: 'Información',
                                            message: 'El conduce ha sido eliminado!',
                                            button: {
                                                yes: 'OK',
                                            }
                                        });
                                })
                                .catch(error => {
                                    this.$confirm(
                                        {
                                            title: 'Información',
                                            message: 'Hubo un error al eliminar el conduce!',
                                            button: {
                                                yes: 'OK',
                                            }
                                        });
                                    console.log(error);
                                })
                                .finally(() => this.loading = false)
                        }
                    }
                }
            )
        }
    },
    mounted() {
        this.getDeliveryLeads();
    }
}
</script>
