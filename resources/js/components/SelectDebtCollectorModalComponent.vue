<template>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Seleccionar cobrador</h5>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Cobrador</label>
                <multiselect v-model="debtCollector"
                             id="select" label="name" track-by="id" placeholder="Seleccionar cobrador"
                             :multiple="false"
                             open-direction="bottom"
                             :options="debtCollectors"
                             :searchable="true"
                             :loading="loading"
                             :internal-search="false"
                             :clear-on-select="false"
                             :show-no-results="false"
                             :hide-selected="true"
                             @search-change="asyncFind">
                </multiselect>
            </div>
            <div class="mb-3" v-if="displayUpdatePreviousPayment">
                <label class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" v-model="updatePreviousPayments">
                    <span class="form-check-label">Actualizar pagos registrados hasta la fecha.</span>
                </label>
            </div>
        </div>
        <div class="modal-footer">
            <div class="ml-auto">
                <button type="button" data-dismiss="modal" class="btn btn-white mr-auto" @click="$emit('close')">
                    Cerrar
                </button>
                <button type="button" data-dismiss="modal" class="btn btn-primary" @click="store">Guardar</button>
            </div>
        </div>
    </div>
</template>

<script>

import {CONSTS} from '../consts';
import {DebtCollector} from "../models/DebtCollector";
import {debounce, isNull} from "lodash";

const ENDPOINT = CONSTS.HOST + 'debt-collector';

export default {
    props: ['selectedDebtCollectorId', 'displayUpdatePreviousPayment'],
    data: function () {
        return {
            loading: false,
            errored: false,
            saving: false,
            erroredSaving: false,
            debtCollector: null,
            debtCollectors: [],
            updatePreviousPayments: false
        }
    },
    mounted() {
        this.getDebtCollector(this.selectedDebtCollectorId);
        this.asyncFind("");
    },
    methods: {
        asyncFind: debounce(function (query) {
            this.loading = true;
            axios
                .get(ENDPOINT + '/list?search=' + query)
                .then(response => {
                    this.debtCollectors = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                    this.errored = true
                })
                .finally(() => this.loading = false)
        }, 300),
        getDebtCollector: function (debtCollectorId) {

            if (isNull(debtCollectorId)){
                this.debtCollector = new DebtCollector();
                return;
            }

            axios
                .get(`${ENDPOINT}/${debtCollectorId}`)
                .then(response => {
                    const data = response.data.data;
                    this.debtCollector = new DebtCollector(
                        data.id,
                        data.name,
                        data.status,
                        data.commission
                    );
                })
                .catch(error => {
                    console.log(error);
                    this.errored = true
                })
                .finally(() => this.loading = false)
        },
        store: function () {
            this.$emit('close', {...this.debtCollector,  ...{ updatePreviousPayments: this.updatePreviousPayments}});
        }
    }
}
</script>
