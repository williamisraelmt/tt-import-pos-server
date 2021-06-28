<template>
    <div class="modal-content">
        <div class="modal-header" v-if="product">
            <h5 class="modal-title">{{ selectedProductId ? 'Editar' : 'Crear' }} producto {{product.name}}</h5>
        </div>
        <div class="modal-body" v-if="product">
            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select class="form-select" v-model="product.status">
                    <option v-bind:value="true">Activo</option>
                    <option v-bind:value="false">Inactivo</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Marca</label>
                <multiselect v-model="product.productBrand"
                             id="brand" label="name" track-by="id" placeholder="Marca"
                             open-direction="bottom"
                             :options="brands"
                             :searchable="true"
                             :loading="loading"
                             :internal-search="false"
                             :clear-on-select="false"
                             :show-no-results="false"
                             :hide-selected="true"
                             @search-change="asyncFindBrands">
                    <template slot="tag"
                              slot-scope="{ option, remove }">
                <span class="multiselect__tag">
                <span> {{ option.name }}
                    <i aria-hidden="true" tabindex="1" class="multiselect__tag-icon" @click="remove(option)"></i>
                </span>
                    </span>
                    </template>
                    <span slot="noResult">Sin resultados.</span>
                </multiselect>
            </div>
            <div class="mb-3">
                <label class="form-label">Tipo</label>
                <multiselect v-model="product.productType"
                             id="type" label="name" track-by="id" placeholder="Tipo"
                             open-direction="bottom"
                             :options="types"
                             :searchable="true"
                             :loading="loading"
                             :internal-search="false"
                             :allow-empty="true"
                             :clear-on-select="false"
                             :show-no-results="false"
                             :hide-selected="true"
                             @search-change="asyncFindTypes">
                    <template slot="tag"
                              slot-scope="{ option, remove }">
                <span class="multiselect__tag">
                <span> {{ option.name }}
                    <i aria-hidden="true" tabindex="1" class="multiselect__tag-icon" @click="remove(option)"></i>
                </span>
                    </span>
                    </template>
                    <span slot="noResult">Sin resultados.</span>
                </multiselect>
            </div>
            <div class="mb-3">
                <label class="form-label">Departamento</label>
                <multiselect v-model="product.productDepartment"
                             id="department" label="name" track-by="id" placeholder="Departamento"
                             open-direction="bottom"
                             :options="departments"
                             :searchable="true"
                             :loading="loading"
                             :internal-search="false"
                             :clear-on-select="false"
                             :show-no-results="false"
                             :hide-selected="true"
                             @search-change="asyncFindDepartments">
                    <template slot="tag"
                              slot-scope="{ option, remove }">
                <span class="multiselect__tag">
                <span> {{ option.name }}
                    <i aria-hidden="true" tabindex="1" class="multiselect__tag-icon" @click="remove(option)"></i>
                </span>
                    </span>
                    </template>
                    <span slot="noResult">Sin resultados.</span>
                </multiselect>
            </div>
            <div class="mb-3">
                <label class="form-label">Categoría</label>
                <multiselect v-model="product.productCategory"
                             id="category" label="name" track-by="id" placeholder="Categoría"
                             open-direction="bottom"
                             :options="categories"
                             :searchable="true"
                             :loading="loading"
                             :internal-search="false"
                             :clear-on-select="false"
                             :show-no-results="false"
                             :hide-selected="true"
                             @search-change="asyncFindCategories">
                    <template slot="tag"
                              slot-scope="{ option, remove }">
                <span class="multiselect__tag">
                <span> {{ option.name }}
                    <i aria-hidden="true" tabindex="1" class="multiselect__tag-icon" @click="remove(option)"></i>
                </span>
                    </span>
                    </template>
                    <span slot="noResult">Sin resultados.</span>
                </multiselect>
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
import {debounce, isNull} from 'lodash';

const BASE_ENDPOINT = CONSTS.HOST;
const PRODUCT_ENDPOINT = `${BASE_ENDPOINT}product`;
const BRAND_ENDPOINT = `${BASE_ENDPOINT}product-brand/list`;
const CATEGORY_ENDPOINT = `${BASE_ENDPOINT}product-category/list`;
const DEPARTMENT_ENDPOINT = `${BASE_ENDPOINT}product-department/list`;
const MODEL_ENDPOINT = `${BASE_ENDPOINT}product-model/list`;
const TYPE_ENDPOINT = `${BASE_ENDPOINT}product-type/list`;

export default {
    props: ['selectedProductId'],
    data: function () {
        return {
            loading: false,
            errored: false,
            saving: false,
            erroredSaving: false,
            product: null,
            brands: [],
            categories: [],
            departments: [],
            models: [],
            types: [],
        }
    },
    mounted() {
        this.getProduct(this.selectedProductId);
        this.asyncFindBrands("");
        this.asyncFindCategories("");
        this.asyncFindDepartments("");
        // this.asyncFindModels("");
        this.asyncFindTypes("");
    },
    methods: {
        asyncFindBrands: debounce(function (query) {
            this.loading = true;
            axios
                .get(BRAND_ENDPOINT + '?search=' + query)
                .then(response => {
                    this.brands = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                    this.errored = true
                })
                .finally(() => this.loading = false)
        }, 300),
        asyncFindCategories: debounce(function (query) {
            this.loading = true;
            axios
                .get(CATEGORY_ENDPOINT + '?search=' + query)
                .then(response => {
                    this.categories = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                    this.errored = true
                })
                .finally(() => this.loading = false)
        }, 300),
        asyncFindDepartments: debounce(function (query) {
            this.loading = true;
            axios
                .get(DEPARTMENT_ENDPOINT + '?search=' + query)
                .then(response => {
                    this.departments = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                    this.errored = true
                })
                .finally(() => this.loading = false)
        }, 300),
        asyncFindModels: debounce(function (query) {
            this.loading = true;
            axios
                .get(MODEL_ENDPOINT + '?search=' + query)
                .then(response => {
                    this.models = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                    this.errored = true
                })
                .finally(() => this.loading = false)
        }, 300),
        asyncFindTypes: debounce(function (query) {
            this.loading = true;
            axios
                .get(TYPE_ENDPOINT + '?search=' + query)
                .then(response => {
                    this.types = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                    this.errored = true
                })
                .finally(() => this.loading = false)
        }, 300),
        getProduct: function (productId) {

            axios
                .get(`${PRODUCT_ENDPOINT}/${productId}`)
                .then(response => {
                    this.product = response.data.data;
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
                .put(`${PRODUCT_ENDPOINT}/${this.product.id}`, this.product)
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
