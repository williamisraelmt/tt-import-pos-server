<template>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Crear conduce de entrega</h5>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger w-100" v-if="erroredSaving">Los datos insertados son inválidos, revisar que todas las
                        cantidades de bultos sean mayor que 0 y que todos los clientes tengan facturas agregadas.
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-striped table-borderless">
            <thead>
            <tr style="font-size: 0.725rem">
                <th style="width: 50px" class="text-center">ID</th>
                <th style="width: 200px">Cliente</th>
                <th style="width: 300px" class="text-centernpm install vue-numeric-input --save">Facturas</th>
                <th style="width: 100px" class="text-center">Bultos</th>
                <th style="width: 25px"></th>
            </tr>
            </thead>
            <tbody>


            <tr>
                <td colspan="4">
                    <multiselect v-model="customer"
                                 id="add" label="name" track-by="id" placeholder="Agregar cliente a la lista"
                                 open-direction="bottom"
                                 :options="customers"
                                 :searchable="true"
                                 :loading="loading"
                                 :internal-search="false"
                                 :clear-on-select="false"
                                 :show-no-results="false"
                                 :hide-selected="true"
                                 @search-change="asyncFind">
                        <template slot="tag"
                                  slot-scope="{ option, remove }">
                <span class="multiselect__tag">
                <span> {{ option.name }}
                    <i aria-hidden="true" tabindex="1" class="multiselect__tag-icon" @click="remove(option)"></i>
                </span>
                    </span>
                        </template>
                        <template slot="clear" slot-scope="props">
                            <div class="multiselect__clear" v-if="customer"
                                 @mousedown.prevent.stop="clearAll(props.search)"></div>
                        </template>
                        <span slot="noResult">Sin resultados.</span>
                    </multiselect>
                </td>
                <td class="text-center">
                    <a href="javascript:void(0)" class="btn btn-link btn-block"
                       v-bind:disabled="customer === null || customer.value === null" @click="addCustomer()">
                        Agregar
                    </a>
                </td>
            </tr>
            <tr v-for="(selectedCustomer, index) in selectedCustomersLocal">
                <td class="text-center" style="vertical-align: middle">{{selectedCustomer.id}}</td>
                <td style="font-weight: 500; vertical-align: middle">{{selectedCustomer.name}}</td>
                <td>
                    <invoice-select-component
                        :key="selectedCustomer.id"
                        :defaultInvoices="selectedCustomer.invoices"
                                              v-on:invoice-changed="selectedCustomer.invoices = $event"
                                              :customerId="selectedCustomer.id"></invoice-select-component>
                </td>
                <td>
                    <input type="number" class="form-control" v-model="selectedCustomer.packageQuantity"/>
                <td class="text-center">
                    <a href="javascript:void(0)" class="btn btn-link btn-block" @click="removeCustomer(index)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24"
                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z"></path>
                            <line x1="4" y1="7" x2="20" y2="7"></line>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                        </svg>
                    </a>
                </td>
            </tr>

            </tbody>
        </table>
        <div class="modal-footer">
            <div class="ml-auto">
                <button type="button" data-dismiss="modal" class="btn btn-white mr-auto" @click="$emit('close')">Cerrar</button>
                <button type="button" data-dismiss="modal" class="btn btn-primary"
                        v-bind:disabled="!this.selectedCustomersLocal.length" @click="createLeads">Guardar e Imprimir
                </button>
            </div>
        </div>
    </div>
</template>

<script>

    import {CONSTS} from '../consts';
    import {Customer} from '../models/customer';
    import {debounce, cloneDeep} from 'lodash';

    const ENDPOINT = CONSTS.HOST + 'customer/list';
    const SAVE_ENDPOINT = CONSTS.HOST + 'delivery-lead';

    export default {
        name: 'CreateLeadModalComponent',
        props: ['selectedCustomers'],
        data: function () {
            return {
                customer: null,
                customers: [],
                loading: false,
                errored: false,
                saving: false,
                erroredSaving: false,
                selectedCustomersLocal: []
            }
        },
        watch: {
            selectedCustomersLocal: {
                handler: function (selectedCustomersLocal) {
                    console.log(selectedCustomersLocal);
                },
                deep: true
            }
        },
        mounted() {
            this.selectedCustomersLocal = cloneDeep(this.selectedCustomers);
            this.asyncFind("");
        },
        methods: {
            asyncFind: debounce(function (query) {
                this.loading = true;
                axios
                    .get(ENDPOINT + '?search=' + query)
                    .then(response => {
                        this.customers = response.data.data;
                    })
                    .catch(error => {
                        console.log(error);
                        this.errored = true
                    })
                    .finally(() => this.loading = false)
            }, 300),
            // changeInvoice: function (index, $event) {
            //     this.selectedCustomersLocal[index]['invoices'] = $event.map(e => ({id: e.id, value: e.value}));
            // },
            removeCustomer: function (index) {
                this.selectedCustomersLocal = this.selectedCustomersLocal.filter((_, i) => i !== index);
            },
            addCustomer: function () {
                if (this.customer === null || this.customer.id === null) {
                    return;
                }
                if (this.selectedCustomersLocal.some(selectedCustomer => selectedCustomer.id === this.customer.id)) {
                    return;
                }
                this.selectedCustomersLocal.push(new Customer(this.customer.id, this.customer.name));
                this.customer = null;
            },
            createLeads: function () {
                this.saving = true;
                this.erroredSaving = false;
                if (!this.selectedCustomersLocal.length) {
                    this.erroredSaving = true;
                    return;
                }
                if (this.selectedCustomersLocal.some(selectedCustomer => !selectedCustomer.invoices || !selectedCustomer.invoices.length)) {
                    this.erroredSaving = true;
                    return;
                }
                if (this.selectedCustomersLocal.some(selectedCustomer => !selectedCustomer.packageQuantity || selectedCustomer.packageQuantity < 1)) {
                    this.erroredSaving = true;
                    return;
                }
                axios
                    .post(SAVE_ENDPOINT, {leads: this.selectedCustomersLocal})
                    .then(response => {
                        const leadIds = response.data.data;
                        let queryString = '';
                        leadIds.forEach(leadId => {
                            queryString += `lead_id[]=${leadId}&`;
                        });
                        window.location.href = '/delivery-lead/print?' + queryString;
                        this.erroredSaving = false;
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
