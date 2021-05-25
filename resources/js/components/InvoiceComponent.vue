<template>
    <div class="page-wrapper">
        <div class="page-body">
            <div class="container-xl">
                <!-- Page title -->
                <div class="row row-deck row-cards">
                    <div class="col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Facturas</h3>
                            </div>
                            <div class="card-body border-bottom pb-0">
                                <div class="row row-sm">
                                    <div class="col-sm-12 col-md-auto flex-md-grow-1 mb-3">
                                        <input type="text" class="form-control" placeholder="Buscar"
                                               v-model="grid.search">
                                    </div>
                                    <div class="col-auto mb-3">
                                        <odoo-sync-button-component v-on:synced="getInvoices()"></odoo-sync-button-component>
                                    </div>
                                    <div class="col-auto mb-3">
                                  <span class="dropdown ml-1">
                                    <button class="btn btn-white dropdown-toggle"
                                            data-boundary="viewport"
                                            data-toggle="dropdown">Acciones</button>
                                    <div class="dropdown-menu dropdown-menu-right">
<!--                                      <a class="dropdown-item" href="javascript:void(0)" @click="createLeads()">-->
<!--                                        Despachar-->
<!--                                      </a>-->
                                    </div>
                                  </span>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mb-0">
                                <table class="table card-table table-vcenter text-nowrap datatable">
                                    <thead>
                                    <tr>
                                        <th class="w-1">Id</th>
                                        <th>Cliente</th>
                                        <th>Nombre</th>
                                        <th class="text-center">Monto</th>
                                        <th class="text-center">Balance pendiente</th>
                                        <th>Fecha</th>
                                        <th>Fecha vencimiento</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-if="!loading" v-for="invoice in invoices">
                                        <td>
                                            <span class="text-muted">{{invoice.id}}</span>
                                        </td>
                                        <td class="font-weight-bold">
                                            {{invoice.customerName}}
                                        </td>
                                        <td>
                                            {{invoice.displayName}}
                                        </td>
                                        <td class="font-weight-bold" style="text-align: right">
                                            {{invoice.amountTotal | currency}}
                                        </td>
                                        <td class="font-weight-bold" style="text-align: right">
                                            {{invoice.residual | currency}}
                                        </td>
                                        <td>
                                            {{invoice.createDate }}
                                        </td>
                                        <td>
                                            {{invoice.dateDue }}
                                        </td>
                                    </tr>
                                    <tr v-if="!loading && !invoices.length">
                                        <td colspan="7">Sin resultados</td>
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

            </div>
        </div>
    </div>
</template>

<script>
import {debounce} from 'lodash';
import {CONSTS} from '../consts';
import {Grid} from "../models/Grid";

const ENDPOINT = CONSTS.HOST + 'invoice';

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
            invoices: [],
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
                this.getInvoices();
            }, 800),
            deep: true
        },
        currentPage: {
            handler: function (val) {
                this.getInvoices();
            },
            deep: true
        },
    },
    methods: {
        getInvoices: function () {
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
                    this.invoices = response.data.data;
                    this.total = response.data.total;
                    this.pageTotal = Math.ceil((this.total / grid.limit) || 0);
                })
                .catch(error => {
                    console.log(error);
                    this.errored = true
                })
                .finally(() => this.loading = false)
        },
    },
    mounted() {
        this.getInvoices();
    }
}
</script>
