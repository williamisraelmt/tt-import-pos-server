<template>
    <div class="page-wrapper">
        <div class="page-body">
            <div class="container-xl">
                <!-- Page title -->
                <div class="row row-deck row-cards">
                    <div class="col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Pagos</h3>
                            </div>
                            <div class="card-body border-bottom pb-0">
                                <div class="row row-sm">
                                    <div class="col-sm-12 col-md-auto flex-md-grow-1 mb-3">
                                        <input type="text" class="form-control" placeholder="Buscar"
                                               v-model="grid.search">
                                    </div>
                                    <div class="col-auto mb-3">
                                        <odoo-sync-button-component v-on:synced="getPayments()"></odoo-sync-button-component>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mb-0">
                                <table class="table card-table table-vcenter text-nowrap datatable">
                                    <thead>
                                    <tr>
                                        <th class="w-1">Id</th>
                                        <th>Nombre</th>
                                        <th class="w-1 text-center">Monto</th>
                                        <th class="w-1">Cliente</th>
                                        <th class="w-1 text-center">Cobrador</th>
                                        <th>Facturas afectadas</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-if="!loading" v-for="record in records">
                                        <td>
                                            <span class="text-muted">{{ record.paymentId }}</span>
                                        </td>
                                        <td class="font-weight-bold">
                                            {{ record.paymentName }}
                                        </td>
                                        <td style="text-align: right;" class="font-weight-bold">
                                            {{ record.paymentAmount | currency }}
                                        </td>
                                        <td>
                                            {{ record.customerName }}
                                        </td>
                                        <td>
                                            {{ record.debtCollectorName }}
                                        </td>
                                        <td>
                                            <span class="badge bg-blue-lt m-1" v-for="invoice in record.invoices">
                                             {{ invoice }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="dropdown ml-1">
                                                <button class="btn btn-white btn-sm dropdown-toggle align-text-top"
                                                        data-boundary="viewport"
                                                        data-toggle="dropdown">Acciones</button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                  <a class="dropdown-item" href="javascript:void(0)"
                                                     v-on:click="selectDebtCollector(record.paymentId, record.debtCollectorId)">
                                                    Cambiar cobrador
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
import {debounce, isUndefined} from 'lodash';
import {CONSTS} from '../consts';
import {Grid} from "../models/Grid";
import {Customer} from "../models/Customer";
import CreateLeadModalComponent from "./CreateLeadModalComponent";
import SelectDebtCollectorModalComponent from "./SelectDebtCollectorModalComponent";

const ENDPOINT = CONSTS.HOST + 'payment';
const SYNC_ENDPOINT = CONSTS.HOST + 'sync';

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
            records: [],
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
                this.getPayments();
            }, 800),
            deep: true
        },
        currentPage: {
            handler: function (val) {
                this.getPayments();
            },
            deep: true
        },
    },
    methods: {
        getPayments: function () {
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
                    this.records = response.data.data;
                    this.total = response.data.total;
                    this.pageTotal = Math.ceil((this.total / grid.limit) || 0);
                })
                .catch(error => {
                    console.log(error);
                    this.errored = true
                })
                .finally(() => this.loading = false)
        },
        selectDebtCollector: function (paymentId, debtCollectorId) {
            this.$modal.show(SelectDebtCollectorModalComponent, {
                selectedDebtCollectorId: debtCollectorId
            }, {
                width: '500px',
                height: 'auto',
                clickToClose: false
            }, {
                'before-close': (event) => {
                    if (!isUndefined(event.params)){
                        this.updateDebtCollector(paymentId, event.params.id);
                    }
                }
            })
        },
        updateDebtCollector: function(paymentId, debtCollectorId) {
            this.saving = true;
            this.erroredSaving = false;
            axios
                .put(`${ENDPOINT}/debt-collector/${paymentId}`, {
                    debtCollectorId
                })
                .then(response => {
                    this.erroredSaving = false;
                    this.$confirm(
                        {
                            title: 'Información',
                            message: 'El cobrador ha sido actualizado!',
                            button: {
                                yes: 'OK',
                            }
                        });
                    this.getPayments();
                })
                .catch(error => {
                    this.erroredSaving = true
                    this.$confirm(
                        {
                            title: 'Información',
                            message: 'Hubo un error al actualizar el cobrador, favor contactar al administrador del sistema!',
                            button: {
                                yes: 'OK',
                            }
                        });
                })
                .finally(() => this.saving = false)
        }
    },
    mounted() {
        this.getPayments();
    }
}
</script>
