<template>
    <div class="page-wrapper">
        <div class="page-body">
            <div class="container-xl">
                <!-- Page title -->
                <div class="row row-deck row-cards">
                    <div class="col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Cálculo de Comisiones</h3>
                            </div>
                            <div class="card-body border-bottom pb-0">
                                <div class="row row-sm">
                                    <div class="col mb-3">
                                        <date-range-picker
                                            ref="picker"
                                            :locale-data="{
                                        firstDay: 1,
                                        format: 'yyyy-mm-dd',
                                        applyLabel: 'Aplicar',
                                        cancelLabel: 'Cancelar',
                                        daysOfWeek: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
                                        monthNames: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                                        customRangeLabel: 'Rango personalizado',

                                    }"
                                            :maxDate="maxDate"
                                            :singleDatePicker="false"
                                            :timePicker="false"
                                            :opens="'right'"
                                            :timePicker24Hour="false"
                                            :showWeekNumbers="false"
                                            :showDropdowns="false"
                                            :autoApply="false"
                                            v-model="grid"
                                            @update="updateValues"
                                        >
                                        </date-range-picker>
                                    </div>
                                    <div class="col-auto mb-3">
                                        <odoo-sync-button-component v-on:synced="getDebtCollectorsReport()"></odoo-sync-button-component>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mb-0">
                                <table class="table card-table table-vcenter text-nowrap datatable  table-striped">
                                    <thead>
                                    <tr>
                                        <th>Cobrador</th>
                                        <th class="w-1 text-center">Cobros realizados</th>
                                        <th class="w-1 text-center">Facturas afectadas</th>
                                        <th class="w-1 text-center">Total</th>
                                        <th class="w-1 text-center">Sub-total</th>
                                        <th class="w-1 text-center">ITBIS</th>
                                        <th class="w-1 text-center">% Comisión</th>
                                        <th style="text-align: right;">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-if="!loading" v-for="record in records">
                                        <td class="font-weight-bold">
                                            {{ record.debtCollectorName }}
                                        </td>
                                        <td class="text-center">
                                            {{ record.paymentsCount }}
                                        </td>
                                        <td class="text-center">
                                            {{ record.invoicesCount }}
                                        </td>
                                        <td class="font-weight-bold">
                                            {{ record.amount | currency }}
                                        </td>
                                        <td class="font-weight-bold">
                                            {{ (record.amount * .82) | currency }}
                                        </td>
                                        <td class="font-weight-bold">
                                            {{ (record.amount * .18) | currency }}
                                        </td>
                                        <td>
                                            <div class="input-group mb-2" style="width: 150px;">
                                                  <span class="input-group-text">
                                                    %
                                                  </span>
                                                <input type="number" class="form-control" v-model="record.commission"
                                                       placeholder="%">
                                            </div>
                                        </td>
                                        <td style="text-align: right;" class="font-weight-bold">
                                            {{ ((record.amount * .82) * (record.commission / 100)) | currency }}
                                        </td>
                                    </tr>
                                    <tr v-if="!loading && !records.length">
                                        <td class="text-center" colspan="8">Sin resultados.</td>
                                    </tr>
                                    <tr v-if="loading">
                                        <td colspan="8">Cargando...</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>

import DateRangePicker from 'vue2-daterange-picker'
import {Grid} from "../models/Grid";
import {Product} from "../models/Product";
import {CONSTS} from "../consts";
import moment from 'moment';

const ENDPOINT = CONSTS.HOST + 'commission-calculator';
const SYNC_ENDPOINT = CONSTS.HOST + 'sync';

export default {
    components: {DateRangePicker},
    data: function () {
        return {
            maxDate: moment().add(1, 'd').format('YYYY-MM-DD').toString(),
            records: [],
            grid: {
                startDate: new Date(),
                endDate: new Date()
            },
            loading: true,
            errored: false
        }
    },
    methods: {
        getDebtCollectorsReport() {
            this.loading = true;
            const startDate = moment(this.grid.startDate).format('YYYY-MM-DD').toString();
            const endDate = moment(this.grid.endDate).format('YYYY-MM-DD').toString();

            axios
                .post(`${ENDPOINT}/debt-collector-payment`, {
                    startDate, endDate
                })
                .then(response => {
                    console.log(response.data);
                    this.records = response.data;
                })
                .catch(error => {
                    console.log(error);
                    this.errored = true
                })
                .finally(() => this.loading = false)
        },
        updateValues(dateRange) {
            this.grid = dateRange;
            this.getDebtCollectorsReport();
        }
    },
    mounted() {
        this.getDebtCollectorsReport();
    },
}
</script>
