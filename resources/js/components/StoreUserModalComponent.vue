<template>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ selectedUserId ? 'Editar' : 'Crear' }} usuario</h5>
        </div>
        <div class="modal-body" v-if="user">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger w-100" v-if="erroredSaving">Los datos insertados son inválidos,
                        revisar que este nombre o # de identificación no exista.
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="name">Nombre</label>
                <input type="text" class="form-control" v-model="user.fullName" name="name" id="name"
                       placeholder="Nombre del usuario">
            </div>
            <div class="mb-3">
                <label class="form-label" for="name">Cédula o # de pasaporte (Este será el nombre de usuario)</label>
                <input type="text" class="form-control" v-model="user.identification" name="identification"
                       id="identification" placeholder="xxx-xxxxxxx-x">
            </div>
            <div class="mb-3">
                <label class="form-label" for="email">Correo</label>
                <input type="email" class="form-control" v-model="user.email" id="email" name="email"
                       placeholder="xxx@xxx.xxx">
            </div>
            <div class="mb-3">
                <label class="form-label" for="phone">Teléfono</label>
                <input type="phone" class="form-control" v-model="user.phone" id="phone" name="phone"
                       placeholder="xxx-xxx-xxxx">
            </div>
            <div class="mb-3" v-if="!selectedUserId">
                <label class="form-label" for="password">Clave</label>
                <input type="password" class="form-control" v-model="user.password" id="password" name="password"
                       placeholder="****">
            </div>
            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select class="form-select" v-model="user.status">
                    <option v-bind:value="true">Activo</option>
                    <option v-bind:value="false">Inactivo</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-check form-switch">
                    <input class="form-check-input" type="checkbox">
                    <span class="form-check-label">Es administrador?</span>
                </label>
            </div>

            <div class="mb-3">
                <label class="form-check form-switch">
                    <input class="form-check-input" type="checkbox">
                    <span class="form-check-label">Es cliente?</span>
                </label>
            </div>

            <div class="mb-3">
                <label class="form-check form-switch">
                    <input class="form-check-input" type="checkbox">
                    <span class="form-check-label">Es cobrador?</span>
                </label>
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
import {User} from "../models/User";
import {debounce, isNull} from 'lodash';

const ENDPOINT = CONSTS.HOST + 'user';
const ROLES_ENDPOINT = CONSTS.HOST + 'role/list';
const CUSTOMER_ENDPOINT = CONSTS.HOST + 'customer/list';

export default {
    props: ['selectedUserId'],
    data: function () {
        return {
            loading: false,
            errored: false,
            saving: false,
            erroredSaving: false,

            user: null,

            role: null,
            roles: [],

            customers: []
        }
    },
    mounted() {
        this.getUser(this.selectedUserId);
    },
    methods: {
        getUser: function (userId) {

            if (isNull(userId)) {
                this.user = new User();
                return;
            }

            axios
                .get(`${ENDPOINT}/${userId}`)
                .then(response => {
                    this.user = response.data.data;
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
                    id: this.user.id,
                    fullName: this.user.fullName,
                    // name: this.user.name,
                    identification: this.user.identification,
                    email: this.user.email,
                    phone: this.user.phone,
                    status: this.user.status ? '1' : '0',
                    password: this.user.password,
                    roles: this.user.roles,
                    customers: this.user.customers
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
        },
        asyncFindRole: debounce(function (query) {
            this.loading = true;
            axios
                .get(ROLES_ENDPOINT + '?search=' + query)
                .then(response => {
                    this.roles = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                    this.roles = [];
                })
                .finally(() => this.loading = false)
        }, 300),
        asyncFindCustomer: debounce(function (query) {
            this.loading = true;
            axios
                .get(CUSTOMER_ENDPOINT + '?search=' + query)
                .then(response => {
                    this.customers = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                    this.customers = [];
                })
                .finally(() => this.loading = false)
        }, 300),
        addRole: function () {
            if (this.user.roles.some(role => role.id === this.role.id)) {
                return;
            }
            this.user.roles.push(this.role);
            this.role = null;
        }
    }
}
</script>
