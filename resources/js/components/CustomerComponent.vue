<template>
    <div class="page-wrapper">
        <div class="page-body">
            <div class="container-xl">
                <!-- Page title -->
                <div class="row row-deck row-cards">
                    <div class="col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Clientes</h3>
                            </div>
                            <div class="card-body border-bottom pb-0">
                                <div class="row row-sm">
                                    <div class="col-sm-12 col-md-auto flex-md-grow-1 mb-3">
                                        <input type="text" class="form-control" placeholder="Buscar"
                                               v-model="grid.search">
                                    </div>
                                    <div class="col-auto mb-3">
                                        <odoo-sync-button-component v-on:synced="getCustomers()"></odoo-sync-button-component>
                                    </div>
                                    <div class="col-auto mb-3">
                                  <span class="dropdown ml-1">
                                    <button class="btn btn-white dropdown-toggle"
                                            data-boundary="viewport"
                                            data-toggle="dropdown">Acciones</button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                      <a class="dropdown-item" href="javascript:void(0)" @click="createLeads()">
                                        Crear conduces
                                      </a>
                                    </div>
                                  </span>
                                    </div>
                                </div>
                                <div class="row row-sm">
                                    <div class="col mb-3">
                                        <label class="form-label">Clientes seleccionados:</label>

                                        <div class="choices" data-type="select-one" tabindex="0" role="listbox"
                                             aria-haspopup="true" aria-expanded="false">
                                            <div class="form-control">
                                                <div class="choices__list choices__list--single">
                                                    <div class="choices__item choices__item--selectable" data-item=""
                                                         data-id="1" data-value="copy" aria-selected="true">
                                                        <span class="dropdown-item-indicator">
                                                            <span class="badge bg-muted-lt" v-if="!selectedCustomers.length">No hay clientes seleccionados.</span>
                                                        </span>
                                                        <span class="dropdown-item-indicator"
                                                              v-for="(selectedCustomer, index) in selectedCustomers">
                                                            <span class="badge bg-blue-lt">
                                                                {{selectedCustomer.name}}
                                                                <a href="javascript:void(0)" @click="removeSelectedCustomer(index)"
                                                                   class="remove" tabindex="-1"
                                                                   title="Remove">×</a>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mb-0">
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
                                        <th>Dirección</th>
                                        <th>Teléfono</th>
                                        <th>Cobrador</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-if="!loading" v-for="customer in customers">
                                        <td><input class="form-check-input m-0 align-middle" type="checkbox"
                                                   v-bind:value="customer"
                                                   v-model="selectedCustomers"
                                                   aria-label="Seleccionar">
                                        </td>
                                        <td>
                                            <span class="text-muted">{{customer.id}}</span>
                                        </td>
                                        <td class="font-weight-bold">
                                            {{customer.name}}
                                        </td>
                                        <td>
                                            {{customer.address | truncate(50, '...')}}
                                        </td>
                                        <td>
                                            {{customer.phone}}
                                        </td>
                                        <td>
                                            {{customer.debtCollectorName}}
                                        </td>
                                        <td class="text-center">
                                            <span class="dropdown ml-1">
                                                <button class="btn btn-white btn-sm dropdown-toggle align-text-top"
                                                        data-boundary="viewport"
                                                        data-toggle="dropdown">Acciones</button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                  <a class="dropdown-item" href="javascript:void(0)"
                                                     v-on:click="selectDebtCollector(customer.id, customer.debtCollectorId)">
                                                    Cambiar cobrador
                                                  </a>
                                                </div>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr v-if="!loading && !customers.length">
                                        <td colspan="7" class="text-center">Sin resultados.</td>
                                    </tr>
                                    <tr v-if="loading">
                                        <td colspan="7">Cargando...</td>
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

const ENDPOINT = CONSTS.HOST + 'customer';
const SYNC_ENDPOINT = CONSTS.HOST + 'sync';

export default {
    data: function () {
        return {
            selectedCustomers: [],
            bootstrapPaginationClasses: {
                ul: 'pagination m-0 ml-auto',
                li: 'page-item',
                liActive: 'active',
                liDisable: 'disabled',
                button: 'page-link'
            },
            customers: [],
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
                this.getCustomers();
            }, 800),
            deep: true
        },
        currentPage: {
            handler: function (val) {
                this.getCustomers();
            },
            deep: true
        },
    },
    methods: {
        createLeads: function () {
            this.$modal.show(CreateLeadModalComponent, {
                selectedCustomers: this.selectedCustomers
            }, {
                width: '60%',
                height: 'auto',
                clickToClose: false
            })
        },
        removeSelectedCustomer: function (index) {
            this.selectedCustomers.splice(index, 1);
        },
        getCustomers: function () {
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
                    this.customers = response.data.data.map(
                        (customer) => new Customer(
                            customer.id,
                            customer.name,
                            customer.address,
                            customer.phone,
                            null,
                            customer.debtCollectorId,
                            customer.debtCollectorName
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
        selectDebtCollector: function (customerId, debtCollectorId) {
            this.$modal.show(SelectDebtCollectorModalComponent, {
                selectedDebtCollectorId: debtCollectorId,
                displayUpdatePreviousPayment: true
            }, {
                width: '500px',
                height: 'auto',
                clickToClose: false
            }, {
                'before-close': (event) => {
                    if (!isUndefined(event.params)){
                        console.log(event.params);
                        this.updateDebtCollector(customerId, event.params.id, event.params.updatePreviousPayments);
                    }
                }
            })
        },
        updateDebtCollector: function(customerId, debtCollectorId, updatePrevious) {
            this.saving = true;
            this.erroredSaving = false;
            const updatePreviousPayments = updatePrevious ? '1' : '0';
            axios
                .put(`${ENDPOINT}/debt-collector/${customerId}`, {
                    debtCollectorId,
                    updatePreviousPayments
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
                    this.getCustomers();
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
        this.getCustomers();
    }
}
</script>
