<template>
    <div class="page-wrapper">
        <div class="page-body">
            <div class="container-xl">
                <!-- Page title -->
                <div class="row row-deck row-cards">
                    <div class="col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Usuarios</h3>
                            </div>
                            <div class="card-body border-bottom pb-0">
                                <div class="row row-sm">
                                    <div class="col mb-3">
                                        <input type="text" class="form-control" placeholder="Buscar"
                                               v-model="grid.search">
                                    </div>
                                    <div class="col-auto mb-3">
                                        <odoo-sync-button-component
                                            v-on:synced="getUsers()"></odoo-sync-button-component>
                                    </div>
                                    <div class="col-auto mb-3">
                                  <span class="dropdown ml-1">
                                    <button class="btn btn-white dropdown-toggle"
                                            data-boundary="viewport"
                                            data-toggle="dropdown">Acciones</button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                      <a class="dropdown-item" href="javascript:void(0)" @click="createUser()">
                                        Crear
                                      </a>
                                    </div>
                                  </span>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mb-0">
                                <table class="table card-table table-vcenter text-nowrap datatable  table-striped">
                                    <thead>
                                    <tr>
                                        <th class="w-1">Id</th>
                                        <th>Nombre</th>
                                        <th>Usuario</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>Cédula / Pasaporte</th>
                                        <th>Estado</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-if="!loading" v-for="user in users">
                                        <td>
                                            <span class="text-muted">{{ user.id }}</span>
                                        </td>
                                        <td class="font-weight-bold">
                                            {{ user.fullName }}
                                        </td>
                                        <td class="font-weight-bold">
                                            {{ user.name }}
                                        </td>
                                        <td>
                                            {{ user.email }}
                                        </td>
                                        <td>
                                            {{ user.phone }}
                                        </td>
                                        <td>
                                            {{ user.identification }}
                                        </td>
                                        <td>
                                            <template v-if="user.status">
                                                <span class="badge bg-success me-1" style="vertical-align: middle"></span> Activo
                                            </template>
                                            <template v-if="!user.status">
                                                <span class="badge bg-warning me-1" style="vertical-align: middle"></span>Inactivo
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <span class="dropdown ml-1">
                                                <button class="btn btn-white btn-sm dropdown-toggle align-text-top"
                                                        data-boundary="viewport"
                                                        data-toggle="dropdown">Acciones</button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                  <a class="dropdown-item" href="#" @click="editUser(user.id)">
                                                    Editar
                                                  </a>
                                                </div>
                                          </span>
                                        </td>
                                    </tr>
                                    <tr v-if="!loading && !users.length">
                                        <td class="text-center" colspan="9">Sin resultados.</td>
                                    </tr>
                                    <tr v-if="loading">
                                        <td colspan="9">Cargando...</td>
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
import {User} from "../models/User";
import StoreUserModalComponent from "./StoreUserModalComponent";

const ENDPOINT = CONSTS.HOST + 'user';

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
            users: [],
            pageTotal: 0,
            total: 0,
            currentPage: 1,
            grid: {
                limit: 10,
                sortBy: [],
                search: "",
            },
            loading: true,
            errored: false
        }
    },
    watch: {
        grid: {
            handler: debounce(function (val) {
                this.getUsers();
            }, 800),
            deep: true
        },
        currentPage: {
            handler: function (val) {
                this.getUsers();
            },
            deep: true
        },
    },
    methods: {
        getUsers: function () {
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

                    this.users = response.data.data.map(
                            (user) => new User(
                                user.id,
                                user.name,
                                user.fullName,
                                user.identification,
                                user.email,
                                user.phone,
                                user.status
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
        createUser: function() {
            this.$modal.show(StoreUserModalComponent, {
                selectedUserId: null
            }, {
                width: '500px',
                height: 'auto',
                clickToClose: false
            }, {
                'before-close': (event) => {
                    if (!isUndefined(event.params)){
                        this.getUsers();
                    }
                }
            })
        },
        editUser: function(userId) {
            this.$modal.show(StoreUserModalComponent, {
                selectedUserId: userId
            }, {
                width: '500px',
                height: 'auto',
                clickToClose: false
            }, {
                'before-close': (event) => {
                    if (!isUndefined(event.params)){
                        this.getUsers();
                    }
                }
            })
        },
    },
    mounted() {
        this.getUsers();
    },
}
</script>
