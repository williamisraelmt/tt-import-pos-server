<template>

    <multiselect v-model="selectedInvoices"
                 v-bind:id="customerId" label="displayName" track-by="id" placeholder=""
                 open-direction="bottom" :options="invoices" :multiple="true" :searchable="true" :loading="loading"
                 :internal-search="false" :clear-on-select="false" :close-on-select="false" :options-limit="300"
                 :max-height="600" :show-no-results="false" :hide-selected="true"
                 @input="invoiceChanged($event)"
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
            <div class="multiselect__clear" v-if="selectedInvoices.length"
                 @mousedown.prevent.stop="clearAll(props.search)"></div>
        </template>
        <span slot="noResult">Sin resultados.</span>
    </multiselect>
</template>

<script>

    import {CONSTS} from '../consts';

    const ENDPOINT = CONSTS.HOST + 'customer/invoices';
    import {debounce} from 'lodash';

    export default {
        name: 'InvoiceSelectComponent',
        props: ['customerId'],
        mounted() {
            this.asyncFind("");
        },
        data: function () {
            return {
                selectedInvoices: [],
                invoices: [],
                loading: true,
                errored: false,
            }
        },
        methods: {
            asyncFind: function (query) {
                this.loading = true;
                axios
                    .get(ENDPOINT + '/' + this.customerId + '?search=' + query)
                    .then(response => {
                        this.invoices = response.data.data;
                    })
                    .catch(error => {
                        console.log(error);
                        this.errored = true
                    })
                    .finally(() => this.loading = false)
            },
            clearAll() {
                this.selectedInvoices = [];
            },
            invoiceChanged($event){
                this.$emit('invoice-changed', $event);
            }
        }
    }
</script>
