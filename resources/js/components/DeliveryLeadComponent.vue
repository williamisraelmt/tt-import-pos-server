<template>
    <div class="container-xl">
        <!-- Page title -->
        <div class="row row-deck row-cards">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Conduces</h3>
                    </div>
                    <div class="card-body border-bottom py-3">
                        <div class="row row-sm">
                            <div class="col mb-3">
                                <input type="text" class="form-control" placeholder="Buscar" v-model="grid.search">
                            </div>
                            <div class="col-auto mb-3">
                                <button class="btn btn-primary ml-1"  @click="createLeads()">Crear conduces</button>
                            </div>
                        </div>
                        <div class="row row-sm">
                            <div class="col mb-3">
                                <label class="form-label">Conduces seleccionados:</label>
                                <div class="selectize-control form-select multi plugin-remove_button">
                                    <div class="selectize-input form-control items not-full has-options has-items mt-1"
                                         style="box-shadow: none; border: 1px solid #e2e3e6">
                                        <span v-if="!selectedDeliveryLeads.length">No hay conduces seleccionados.</span>
                                        <div class="item"
                                             v-for="(selectedDeliveryLead, index) in selectedDeliveryLeads">
                                            {{selectedDeliveryLead.id}}
                                            <a href="javascript:void(0)" @click="removeSelectedDeliveryLead(index)"
                                               class="remove" tabindex="-1"
                                               title="Remove">×</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                            <tr>
                                <th class="w-1"></th>
                                <th class="w-1">Id
<!--                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-dark icon-thick"-->
<!--                                         width="24" height="24" viewBox="0 0 24 24" stroke-width="2"-->
<!--                                         stroke="currentColor" fill="none" stroke-linecap="round"-->
<!--                                         stroke-linejoin="round">-->
<!--                                        <path stroke="none" d="M0 0h24v24H0z"></path>-->
<!--                                        <polyline points="6 15 12 9 18 15"></polyline>-->
<!--                                    </svg>-->
                                </th>
                                <th>Cliente</th>
                                <th>Facturas</th>
                                <th>Bultos</th>
                                <th>Fecha</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-if="!loading" v-for="(deliveryLead,index) in deliveryLeads">
                                <td><input class="form-check-input m-0 align-middle" type="checkbox"
                                           v-bind:value="deliveryLead"
                                           v-model="selectedDeliveryLeads"
                                           aria-label="Seleccionar">
                                </td>
                                <td>
                                    <span class="text-muted">{{deliveryLead.id}}</span>
                                </td>
                                <td class="font-weight-bold">
                                    {{deliveryLead.customerName}}
                                </td>
                                <td>
                                    {{deliveryLead.invoices}}
                                </td>
                                <td class="font-weight-bold">
                                    {{deliveryLead.packageQuantity}}
                                </td>
                                <td>
                                    {{deliveryLead.createdAt}}
                                </td>
                                <td>
                                    
                                    <span class="dropdown ml-1">
                                        <button class="btn btn-white btn-sm dropdown-toggle align-text-top"
                                                data-boundary="viewport" data-toggle="dropdown">Acciones</button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                          <a class="dropdown-item" href="#" @click="printDeliveryLead(deliveryLead)">
                                            Imprimir
                                          </a>
                                          <a class="dropdown-item" href="javascript:void(0)" @click="deleteDeliveryLead(deliveryLead.id)">
                                            Borrar
                                          </a>
                                        </div>
                                  </span>
                                </td>
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
                        <v-pagination class="m-0 ml-auto" v-model="currentPage" :classes="bootstrapPaginationClasses"
                                      :page-count="pageTotal"></v-pagination>
                    </div>
                </div>
            </div>
        </div>

        <vue-confirm-dialog></vue-confirm-dialog>
    </div>
</template>

<script>
    import {debounce} from 'lodash';
    import {CONSTS} from '../consts';
    import {Grid} from "../models/grid";
    import {DeliveryLead} from "../models/delivery-lead";
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
                a.href = '/delivery-lead/print?' + queryString;
                a.click();
            },
            printDeliveryLead: function(deliveryLead){
                this.printLeads([deliveryLead]);
            },
            printBulkDeliveryLead: function(){
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
