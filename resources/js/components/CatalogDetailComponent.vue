<template>
    <div>
        <shop-header-component></shop-header-component>
        <div class="navbar-expand-md">
            <div id="navbar-menu">
                <div class="navbar navbar-transparent">
                    <div class="container-xl">
                        <div class="input-group w-100">
                            <input type="text" class="form-control" placeholder="Search…"
                                   aria-label="Search in website" v-model="search">
                            <button class="btn btn-primary input-group-append" v-on:click="paginate(1)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24"
                                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <circle cx="10" cy="10" r="7"></circle>
                                    <line x1="21" y1="21" x2="15" y2="15"></line>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content pt-0" v-if="product">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="container-xl">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <inner-image-zoom
                                    v-bind:src="`/api/catalog/photo/${product.id}/${product.defaultPhotoUrl}`"
                                    v-bind:zoomSrc="`/api/catalog/photo/${product.id}/${product.defaultPhotoUrl}`"
                                    :fullscreenOnMobile="true"
                                    v-bind:zoomScale="1.5"
                                />
                            </div>
                            <div class="col-12 col-md-6 mt-2 mt-md-0">
                                <h2>{{ product.name }}</h2>
                                <ul class="list-unstyled">
                                    <li>Referencia: <b>{{ product.defaultCode }}</b></li>
                                    <li class="mt-1">Marca:
                                        <b>{{ product.productBrandId ? product.productBrand.name : ' - ' }}</b>
                                    </li>
                                    <li class="mt-1">Tipo:
                                        <b>{{ product.productTypeId ? product.productType.name : ' - ' }}</b>
                                    </li>
                                    <li class="mt-1">Categoría:
                                        <b>{{ product.productCategoryId ? product.productCategory.name : ' - ' }}</b>
                                    </li>
                                </ul>
                                <!--                                <p>-->
                                <!--                                    <button class="btn btn-round btn-danger" type="button"><i class="fa fa-shopping-cart"></i>-->
                                <!--                                        Add to Cart-->
                                <!--                                    </button>-->
                                <!--                                </p>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {Product} from "../models/Product";
import {CONSTS} from "../consts";
import {parseCatalogRouteParameters} from "../helpers/parse-catalog-route-parameters";
import InnerImageZoom from 'vue-inner-image-zoom';

const ENDPOINT = CONSTS.HOST + 'catalog/product';

export default {
    components: {
        'inner-image-zoom': InnerImageZoom
    },
    data: function () {
        return {
            search: "",
            productId: null,
            product: null,
            options: {
                toolbar: false,
                url: '',
                initialViewIndex: 1
            },
        }
    },
    mounted() {
        this.productId = this.$route['query']['product_id'];
        this.getProduct(this.productId);
        this.loadCatalogUsingRouteParameters();
    },
    methods: {
        getProduct: function (productId) {
            axios
                .get(`${ENDPOINT}/${productId}`)
                .then(response => {
                    this.product = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                    this.errored = true
                })
                .finally(() => this.loading = false)
        },
        loadCatalogUsingRouteParameters: function () {
            const params = parseCatalogRouteParameters(this.$route.query);
            this.search = params.search;
        },
        paginate: function (val) {
            this.$router.push({path: '/catalog', query: {search: this.search, currentPage: val.toString()}})
        },
    }
}
</script>
