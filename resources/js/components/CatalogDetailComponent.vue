<template>
<!--    <div>-->
<!--        <shop-header-component></shop-header-component>-->

<!--        <div class="content pt-0">-->
<!--            <div class="page-wrapper">-->
<!--                <div class="page-body mt-0">-->
<!--                    <div class="container-xl">-->
<!--                        <div class="row">-->
<!--                            <div class="col-md-6">-->
<!--                                <div class="pro-img-details"><img src="https://via.placeholder.com/550x380/FFB6C1/000000"-->
<!--                                                                  alt=""></div>-->
<!--                                <div v-viewer="options" class="images clearfix">-->
<!--                                    <template v-for="{source, thumbnail} in images">-->
<!--                                        <img :src="thumbnail" :data-source="source" class="image" :key="source" :alt="source.split('?image=').pop()">-->
<!--                                    </template>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="col-md-6"><h4 class="pro-d-title"><a href="#" class=""> Leopard Shirt Dress </a></h4>-->
<!--                                <p> Praesent ac condimentum felis. Nulla at nisl orci, at dignissim dolor, The best product-->
<!--                                    descriptions address your ideal buyer directly and personally. The best product descriptions-->
<!--                                    address your ideal buyer directly and personally.</p>-->
<!--                                <div class="product_meta"><span class="posted_in"> <strong>Categories:</strong> <a rel="tag"-->
<!--                                                                                                                   href="#">Jackets</a>, <a-->
<!--                                    rel="tag" href="#">Men</a>, <a rel="tag" href="#">Shirts</a>, <a rel="tag"-->
<!--                                                                                                     href="#">T-shirt</a>.</span>-->
<!--                                    <span class="tagged_as"><strong>Tags:</strong> <a rel="tag" href="#">mens</a>, <a rel="tag"-->
<!--                                                                                                                      href="#">womens</a>.</span>-->
<!--                                </div>-->
<!--                                <div class="m-bot15"><strong>Price : </strong> <span class="amount-old">$544</span> <span-->
<!--                                    class="pro-price"> $300.00</span></div>-->
<!--                                <div class="form-group"><label>Quantity</label> <input type="quantiy" placeholder="1"-->
<!--                                                                                       class="form-control quantity"></div>-->
<!--                                <p>-->
<!--                                    <button class="btn btn-round btn-danger" type="button"><i class="fa fa-shopping-cart"></i>-->
<!--                                        Add to Cart-->
<!--                                    </button>-->
<!--                                </p>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
</template>

<script>
import {Product} from "../models/Product";
import {CONSTS} from "../consts";

const ENDPOINT = CONSTS.HOST + 'product';

export default {
    name: "CatalogDetailComponent",
    mounted() {

    },
    methods: {
    }
}
</script>
