/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import CatalogComponent from "./components/CatalogComponent";
import CatalogDetailComponent from "./components/CatalogDetailComponent";

require('@tabler/core/dist/js/tabler.min');
require('./bootstrap');

import VueJsModal from 'vue-js-modal';
import Multiselect from 'vue-multiselect'
import VueConfirmDialog from 'vue-confirm-dialog';
import VTooltip from 'v-tooltip'
import VueSelectImage from 'vue-select-image'
import Vue2Filters from 'vue2-filters'
import VueRouter from 'vue-router'
import Paginate from 'vuejs-paginate'

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

/**
 * Internal libraries
 */

Vue.component('invoice-component', require('./components/InvoiceComponent.vue').default);
Vue.component('customer-component', require('./components/CustomerComponent.vue').default);
Vue.component('delivery-lead-component', require('./components/DeliveryLeadComponent.vue').default);
Vue.component('invoice-select-component', require('./components/InvoiceSelectComponent.vue').default);
Vue.component('product-component', require('./components/ProductComponent.vue').default);
Vue.component('commission-calculator-component', require('./components/CommissionCalculatorComponent.vue').default);
Vue.component('debt-collector-component', require('./components/DebtCollectorComponent.vue').default);
Vue.component('payment-component', require('./components/PaymentComponent.vue').default);
Vue.component('user-component', require('./components/UserComponent.vue').default);

Vue.component('product-photo-modal-component', require('./components/ProductPhotoModalComponent.vue').default);
Vue.component('create-lead-modal-component', require('./components/CreateLeadModalComponent.vue').default);
Vue.component('store-debt-collector-modal-component', require('./components/StoreDebtCollectorModalComponent.vue').default);
Vue.component('select-debt-collector-modal-component', require('./components/SelectDebtCollectorModalComponent.vue').default);
Vue.component('store-product-modal-component', require('./components/StoreProductModalComponent.vue').default);

Vue.component('odoo-sync-button-component', require('./components/OdooSyncButtonComponent.vue').default)

Vue.component('shop-header-component', require('./components/ShopHeaderComponent.vue').default);

Vue.component('user-dropdown-component', require('./components/UserDropDownComponent').default);

Vue.component('user-settings-component', require('./components/UserSettingsComponent').default);

/**
 * External libraries
 */

Vue.component('v-pagination', require('vue-plain-pagination'));

Vue.component('paginate', Paginate);

Vue.use(VueJsModal);

Vue.use(VueConfirmDialog);

Vue.use(VTooltip);

Vue.component('vue-confirm-dialog', VueConfirmDialog.default);

Vue.use(Vue2Filters);

// register globally
Vue.component('multiselect', Multiselect);

Vue.use(VueRouter);

var filter = function (text, length, clamp) {
    clamp = clamp || '...';
    var node = document.createElement('div');
    node.innerHTML = text;
    var content = node.textContent;
    return content.length > length ? content.slice(0, length) + clamp : content;
};

Vue.filter('truncate', filter);

const routes = [
    {
        name: 'catalog',
        path: '/catalog',
        component: CatalogComponent
    },
    {
        name: 'catalog-detail',
        path: '/catalog/detail',
        component: CatalogDetailComponent,
        beforeEnter: require('./guards/product-exists').default
    },
    {
        name: 'default',
        path: '/',
        component: CatalogComponent
    },
]

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const router = new VueRouter({
    routes // short for `routes: routes`
})

const app = new Vue({
    el: '#app',
    router
});
