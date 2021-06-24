<template>
    <div class="modal-content" v-if="selectedProduct">
        <div class="modal-header">
            <h5 class="modal-title">Fotos del producto: <b>{{ selectedProduct.name }}</b></h5>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger w-100" v-if="errored">Hubo un error al cargar los datos.
                    </div>
                    <div class="alert alert-danger w-100" v-if="erroredSaving">Hubo un error al realizar la operación,
                        favor contactar al administrador del sistema.
                    </div>
                </div>
                <div class="col-12 mt-3" style="max-height: 500px; overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-2 col-sm-3" v-for="item in photosList">
                            <div class="card card-sm">
                                <a href="#" class="d-block"><img
                                    :src="`/api/product/photo/${selectedProduct.id}/${item.url}`"
                                    class="card-img-top"></a>
                                <div class="d-flex">
                                    <a href="#" class="card-btn" v-on:click="deletePhoto(item.id)"
                                       style="padding: .5rem .5rem;" aria-label="Eliminar">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/mail -->
                                        <!-- Download SVG icon from http://tabler-icons.io/i/trash -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <line x1="4" y1="7" x2="20" y2="7"/>
                                            <line x1="10" y1="11" x2="10" y2="17"/>
                                            <line x1="14" y1="11" x2="14" y2="17"/>
                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="card-btn" v-on:click="markPhotoAsFavorite(item.id)"
                                       style="padding: .5rem .5rem;" aria-label="Por defecto">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/phone -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             v-bind:class="{ 'text-red': defaultPhoto === item.url }"
                                             class="icon icon-filled" width="24"
                                             height="24" viewBox="0 0 24 24" stroke-width="2"
                                             stroke="currentColor" fill="none" stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path
                                                d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <file-pond
                        name="product_photo"
                        ref="pond"
                        class-name="filepond"
                        label-idle="Click para cargar más"
                        allow-multiple="true"
                        accepted-file-types="image/jpeg, image/png"
                        v-bind:files="myFiles"
                        v-on:init="handleFilePondInit"
                        v-on:error="handleOnError"
                        v-on:onaddfilestart="handleOnAddFileStart"
                        v-on:processfile="handleOnAddFile"
                        v-on:updatefiles="handleOnUpdateFiles"
                        v-bind:server="{
                            process: {
                                url: '/api/product/photos/' + selectedProduct.id,
                                headers
                            }
                        }"/>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="ml-auto">
                <button type="button" data-dismiss="modal" class="btn btn-white mr-auto" @click="$emit('close')">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import {CONSTS} from '../consts';
import {debounce, cloneDeep} from 'lodash';

const ENDPOINT = CONSTS.HOST + 'product';
const PHOTOS_ENDPOINT = ENDPOINT + '/photos';

// Import Vue FilePond
import vueFilePond, {setOptions} from 'vue-filepond';
// Import FilePond styles
import FilePondPluginFileValidateType
    from 'filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.esm.js';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.esm.js';
import VueSelectImage from "vue-select-image";

const FilePond = vueFilePond(
    FilePondPluginFileValidateType
);

export default {
    name: 'ProductPhotoModalComponent',
    props: ['selectedProduct'],
    components: {
        FilePond,
        VueSelectImage
    },
    data: function () {
        return {
            loading: false,
            defaultPhoto: null,
            photosList: [],
            errored: false,
            erroredSaving: false,
            myFiles: [],
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }
    },
    watch: {},
    mounted() {
        this.getProductPhotos(this.selectedProduct.id);
    },
    methods: {
        getProductPhotos: function (productId) {
            this.loading = true;
            axios
                .get(`${PHOTOS_ENDPOINT}/${productId}`)
                .then(response => {
                    this.defaultPhoto = response.data.data['default'];
                    this.photosList = response.data.data['list'];

                    console.log(this.defaultPhoto, this.list);

                    // cloneDeep(.map(l => ({
                    //     id: l['id'],
                    //     src: `/api/product/photo/${this.selectedProduct.id}/${l['url']}`
                    // })));
                })
                .catch(error => {
                    console.log(error);
                    this.errored = true
                })
                .finally(() => this.loading = false)
        },
        handleFilePondInit: function () {
            this.erroredSaving = false;
        },
        handleOnError: function (e) {
            this.erroredSaving = true;
            console.log(e);
        },
        handleOnUpdateFiles: function (e) {
            console.log(e);
        },
        handleOnAddFile: function (e) {
            this.erroredSaving = false;
            this.getProductPhotos(this.selectedProduct.id);
        },
        handleOnAddFileStart: function (e) {
            this.erroredSaving = false;
        },
        deletePhoto: function (id) {
            this.loading = true;
            axios
                .delete(`${PHOTOS_ENDPOINT}/${this.selectedProduct.id}/${id}`)
                .then(response => {
                    this.getProductPhotos(this.selectedProduct.id);
                })
                .catch(error => {
                    console.log(error);
                    this.errored = true
                })
                .finally(() => this.loading = false)
        },
        markPhotoAsFavorite: function (id) {
            this.loading = true;
            axios
                .put(`${ENDPOINT}/${this.selectedProduct.id}/favorite-photo/${id}`)
                .then(response => {
                    this.getProductPhotos(this.selectedProduct.id);
                })
                .catch(error => {
                    this.errored = true
                })
                .finally(() => this.loading = false)
        }
    },
}
</script>
