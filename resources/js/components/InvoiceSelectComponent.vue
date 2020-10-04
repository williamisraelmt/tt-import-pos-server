<template>

    <div>

        <multiselect v-model="selectedInvoices"
                     v-bind:id="customerId" label="displayName" track-by="id" placeholder=""
                     open-direction="bottom" :options="list" :multiple="true" :searchable="true" :loading="loading"
                     :internal-search="false" :clear-on-select="false" :close-on-select="false" :options-limit="300"
                     :max-height="600" :show-no-results="false" :hide-selected="true"
                     @search-change="asyncFind">
            <template slot="tag"
                      slot-scope="{ option, remove }">
                <span class="multiselect__tag">
                <span> {{ option.displayName }}
                    <i aria-hidden="true" tabindex="1" class="multiselect__tag-icon" @click="remove(option)"></i>
                </span>
                    </span>
            </template>
            <template slot="clear" slot-scope="props">
                <div class="multiselect__clear" v-if="selectedInvoices"
                     @mousedown.prevent.stop="clearAll(props.search)"></div>
            </template>
            <span slot="noResult">Sin resultados.</span>
        </multiselect>
    </div>
</template>

<script>

    import {CONSTS} from '../consts';

    const ENDPOINT = CONSTS.HOST + 'customer/invoices';
    import {debounce} from 'lodash';

    export default {
        name: 'InvoiceSelectComponent',
        props: ['customerId', 'defaultInvoices'],
        mounted() {
            this.selectedInvoices = this.defaultInvoices;
            this.asyncFind("");
        },
        data: function () {
            return {
                selectedInvoices: [],
                list: [],
                loading: true,
                errored: false,
            }
        },
        watch: {
          selectedInvoices: function(e) {
              this.$emit('invoice-changed', e);
          }
        },
        methods: {
            asyncFind: function (query) {
                this.loading = true;
                axios
                    .get(ENDPOINT + '/' + this.customerId + '?search=' + query)
                    .then(response => {
                        this.list = response.data.data;
                    })
                    .catch(error => {
                        console.log(error);
                        this.errored = true
                    })
                    .finally(() => this.loading = false)
            },
            clearAll() {
                this.selectedInvoices = [];
            }
        },
    }
</script>
