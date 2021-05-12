<template>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ selectedDebtCollectorId ? 'Editar' : 'Crear' }} cobrador</h5>
        </div>
        <div class="modal-body" v-if="debtCollector">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger w-100" v-if="erroredSaving">Los datos insertados son inválidos,
                        revisar {{selectedDebtCollectorId ? '' : 'que este nombre no exista y'}} que la comisión sea igual o mayor a 0 y menor que 100.
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="name">Nombre</label>
                <input type="text" class="form-control" v-model="debtCollector.name" name="name" id="name" placeholder="Nombre del cobrador">
            </div>
            <div class="mb-3">
                <label class="form-label" for="commission">% de Comisión</label>
                <input type="number" class="form-control" v-model="debtCollector.commission" id="commission" name="commission" placeholder="% de comisión">
            </div>
            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select class="form-select" v-model="debtCollector.status">
                    <option v-bind:value="true">Activo</option>
                    <option v-bind:value="false">Inactivo</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <div class="ml-auto">
                <button type="button" data-dismiss="modal" class="btn btn-white mr-auto" @click="$emit('close')">
                    Cerrar
                </button>
                <button type="button" data-dismiss="modal" class="btn btn-primary" @click="store">Aceptar</button>
            </div>
        </div>
    </div>
</template>

<script>

import {CONSTS} from '../consts';
import {DebtCollector} from "../models/DebtCollector";
import {isNull} from 'lodash';

const ENDPOINT = CONSTS.HOST + 'debt-collector';

export default {
    props: ['selectedDebtCollectorId'],
    data: function () {
        return {
            loading: false,
            errored: false,
            saving: false,
            erroredSaving: false,
            debtCollector: null
        }
    },
    mounted() {
        this.getDebtCollector(this.selectedDebtCollectorId);
    },
    methods: {
        getDebtCollector: function (debtCollectorId) {

            if (isNull(debtCollectorId)) {
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
                        data.commission * 100,
                        data.status
                    );
                })
                .catch(error => {
                    console.log(error);
                    this.errored = true
                })
                .finally(() => this.loading = false)
        },
        store: function () {
            this.saving = true;
            this.erroredSaving = false;
            axios
                .put(ENDPOINT, {
                    id: this.debtCollector.id,
                    name: this.debtCollector.name,
                    status: this.debtCollector.status ? '1' : '0',
                    commission: this.debtCollector.commission
                })
                .then(response => {
                    this.erroredSaving = false;
                    this.$emit('close', response.data);
                })
                .catch(error => {
                    console.log(error);
                    this.erroredSaving = true
                })
                .finally(() => this.saving = false)
        }
    }
}
</script>
