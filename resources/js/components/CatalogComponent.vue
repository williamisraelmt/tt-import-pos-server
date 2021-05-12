<template>
    <div>
        <header class="navbar navbar-expand-md navbar-transparent d-print-none">
            <div class="container-xl">
<!--                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">-->
<!--                    <span class="navbar-toggler-icon"></span>-->
<!--                </button>-->
                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href=".">
                        <img width="110" height="32" alt="Tabler" class="navbar-brand-image">
                    </a>
                </h1>
                <div class="navbar-nav flex-row  order-md-last">
                    <div class="nav-item d-md-flex">
                        <div class="btn-list">
                            <a href="https://github.com/sponsors/codecalm" class="btn btn-outline-secondary disabled" target="_blank" rel="noreferrer">
                                <!-- Download SVG icon from http://tabler-icons.io/i/heart -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <circle cx="6" cy="19" r="2"/>
                                    <circle cx="17" cy="19" r="2"/>
                                    <path d="M17 17h-11v-14h-2"/>
                                    <path d="M6 5l14 1l-1 7h-13"/>
                                </svg> 0 items
                            </a>
                            <a href="https://github.com/sponsors/codecalm" class="btn btn-primary disabled" target="_blank" rel="noreferrer">
                                <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <circle cx="12" cy="7" r="4"/>
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                                </svg>Login
                            </a>
                        </div>
                    </div>
                </div>
<!--                <div class="collapse navbar-collapse" id="navbar-menu">-->
<!--                    <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">-->
<!--                        <ul class="navbar-nav">-->
<!--                            <li class="nav-item active">-->
<!--                                <a class="nav-link" href="./#">-->
<!--                              <span class="nav-link-icon d-md-none d-lg-inline-block">&lt;!&ndash; Download SVG icon from http://tabler-icons.io/i/star &ndash;&gt;-->
<!--                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>-->
<!--                              </span>-->
<!--                                    <span class="nav-link-title">-->
<!--                                First-->
<!--                              </span>-->
<!--                                </a>-->
<!--                            </li>-->
<!--                            <li class="nav-item">-->
<!--                                <a class="nav-link" href="./#">-->
<!--                              <span class="nav-link-icon d-md-none d-lg-inline-block">&lt;!&ndash; Download SVG icon from http://tabler-icons.io/i/star &ndash;&gt;-->
<!--                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>-->
<!--                              </span>-->
<!--                                    <span class="nav-link-title">-->
<!--                                Second-->
<!--                              </span>-->
<!--                                    <span class="badge bg-red">2</span>-->
<!--                                </a>-->
<!--                            </li>-->
<!--                            <li class="nav-item dropdown">-->
<!--                                <a class="nav-link dropdown-toggle" href="#navbar-third" data-bs-toggle="dropdown" role="button" aria-expanded="false">-->
<!--                              <span class="nav-link-icon d-md-none d-lg-inline-block">&lt;!&ndash; Download SVG icon from http://tabler-icons.io/i/star &ndash;&gt;-->
<!--                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>-->
<!--                              </span>-->
<!--                                    <span class="nav-link-title">-->
<!--                                Third-->
<!--                              </span>-->
<!--                                </a>-->
<!--                                <div class="dropdown-menu">-->
<!--                                    <a class="dropdown-item" href="./#">-->
<!--                                        First-->
<!--                                    </a>-->
<!--                                    <a class="dropdown-item" href="./#">-->
<!--                                        Second-->
<!--                                    </a>-->
<!--                                    <a class="dropdown-item" href="./#">-->
<!--                                        Third-->
<!--                                    </a>-->
<!--                                </div>-->
<!--                            </li>-->
<!--                            <li class="nav-item">-->
<!--                                <a class="nav-link disabled" href="./#">-->
<!--                              <span class="nav-link-icon d-md-none d-lg-inline-block">&lt;!&ndash; Download SVG icon from http://tabler-icons.io/i/star &ndash;&gt;-->
<!--                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>-->
<!--                              </span>-->
<!--                                    <span class="nav-link-title">-->
<!--                                Disabled-->
<!--                              </span>-->
<!--                                </a>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                    </div>-->
<!--                </div>-->
            </div>
        </header>
        <div class="navbar-expand-md">
            <div id="navbar-menu">
                <div class="navbar navbar-transparent">
                    <div class="container-xl">
                        <div class="input-group w-100">
                            <input type="text" class="form-control" placeholder="Search…"
                                   aria-label="Search in website" v-model="grid.search">
                            <button class="btn btn-warning input-group-append">
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
        <div class="content pt-0">
            <div class="page-wrapper">
                <div class="page-body mt-0">

                    <div class="container-xl">
                        <div class="row">
<!--                            <div class="col-3">-->
<!--                                <form action="" method="get">-->
<!--                                    <div class="subheader mb-2">Marcas</div>-->
<!--                                    <div class="mb-3">-->
<!--                                        <label class="form-check mb-1">-->
<!--                                            <input type="checkbox" class="form-check-input" name="form-tags[]"-->
<!--                                                   value="business" checked="">-->
<!--                                            <span class="form-check-label">VINI</span>-->
<!--                                        </label>-->
<!--                                        <label class="form-check mb-1">-->
<!--                                            <input type="checkbox" class="form-check-input" name="form-tags[]"-->
<!--                                                   value="evening">-->
<!--                                            <span class="form-check-label">TG77</span>-->
<!--                                        </label>-->
<!--                                    </div>-->

<!--                                    <div class="subheader mb-2">Categorías</div>-->
<!--                                    <div>-->
<!--                                        <label class="form-check mb-1">-->
<!--                                            <input type="checkbox" class="form-check-input" name="form-tags[]"-->
<!--                                                   value="business" checked="">-->
<!--                                            <span class="form-check-label">CAT 1</span>-->
<!--                                        </label>-->
<!--                                        <label class="form-check mb-1">-->
<!--                                            <input type="checkbox" class="form-check-input" name="form-tags[]"-->
<!--                                                   value="evening">-->
<!--                                            <span class="form-check-label">CAT 2</span>-->
<!--                                        </label>-->
<!--                                    </div>-->

<!--                                </form>-->
<!--                            </div>-->
                            <div class="col-12" v-if="!loading && total">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="page-header d-print-none mb-1 mt-0">
                                            <div class="row align-items-center">
                                                <div class="col">
<!--                                                    <h2 class="page-title">-->
<!--                                                        Search results-->
<!--                                                    </h2>-->
                                                    <div class="text-muted mt-1">Mostrando <b>{{total > grid.limit ? grid.limit : total}}</b> de <b>{{total}}</b> entradas.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12" v-for="product in products">
                                        <div class="card mb-3">
                                            <div class="d-flex flex-row">
                                                <div>
                                                    <a href="#" v-if="product.defaultPhotoUrl">
                                                        <img class="p-3 pr-0"
                                                             style="height:150px"
                                                            v-bind:src="`/api/product/photo/${product.id}/${product.defaultPhotoUrl}`"
                                                        ></a>
                                                </div>
                                                <div>
                                                    <div class="card-body">
                                                        <h3 class="card-title"><a v-bind:href="`/product/${product.id}`">{{ product.name }}</a></h3>
                                                        <ul class="text-muted small lh-base list-unstyled">
                                                            <li>Referencia: {{product.defaultCode}}</li>
                                                            <li>Marca: {{ product.brand }}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="d-flex">
                                        <div class="text-muted">
                                            Ver
                                            <div class="mx-2 d-inline-block">
                                                <input type="text" class="form-control" value="8" size="3"
                                                       v-model="grid.limit">
                                            </div>
                                            entradas
                                        </div>
                                        <v-pagination class="m-0" v-model="currentPage"
                                                      :classes="bootstrapPaginationClasses"
                                                      :page-count="pageTotal"></v-pagination>
                                    </div>
                                </div>
                            </div>
                            <div class="col-9 d-flex flex-column justify-content-center" v-if="!loading && !total">
                                <div class="empty">
                                    <div class="empty-img">
                                        <img
                                            src="https://preview.tabler.io/static/illustrations/undraw_printing_invoices_5r4r.svg"
                                            height="128" alt="">
                                    </div>
                                    <p class="empty-title">Sin resultados</p>
                                </div>
                            </div>
                            <div class="col-9 align-items-center" v-if="loading">
                                Cargando...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import {debounce} from "lodash";
import ProductPhotoModalComponent from "./ProductPhotoModalComponent";
import {Grid} from "../models/Grid";
import {Product} from "../models/Product";
import {CONSTS} from "../consts";

const ENDPOINT = CONSTS.HOST + 'product';

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
            products: [],
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
                this.getProducts();
            }, 800),
            deep: true
        },
        currentPage: {
            handler: function (val) {
                this.getProducts();
            },
            deep: true
        },
    },
    methods: {
        getProducts: function () {
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
                    this.products = response.data.data.map(
                        (product) => new Product(
                            product.id,
                            product.name,
                            product.defaultCode,
                            product.status,
                            product.category,
                            product.availableQuantity,
                            product.defaultPhotoUrl,
                            product.brand
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
        }
    },
    mounted() {
        this.getProducts();
    },
}
</script>

<style scoped>

</style>
