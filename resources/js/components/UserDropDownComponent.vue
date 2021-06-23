<template>
    <div class="dropdown">
        <a href="/settings" class="btn btn-outline-dark" data-bs-toggle="dropdown"
           aria-label="Open user menu" aria-expanded="false" v-if="user" rel="noreferrer">
            <!-- Download SVG icon from http://tabler-icons.io/i/user -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                 stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <circle cx="12" cy="7" r="4"/>
                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
            </svg>
            {{ user.fullName }}
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
            <template v-if="menu === 'catalog'">
                <a href="#" class="dropdown-item">Mis facturas</a>
                <a href="#" class="dropdown-item">Mis pagos</a>
                <div class="dropdown-divider"></div>
                <a href="/settings" class="dropdown-item">Configuración</a>
            </template>
            <a href="#" @click="logOut()" class="dropdown-item">Cerrar sesión</a>
        </div>
    </div>
</template>

<script>

import {CONSTS} from '../consts';
import {isNull} from 'lodash';

const LOGGED_ENDPOINT = CONSTS.HOST + 'user/logged';
const LOG_OUT_ENDPOINT = CONSTS.HOST + 'user/logout';

export default {
    props: {
        menu: String
    },
    data: function () {
        return {
            user: null
        }
    },
    methods: {
        getUser: function () {
            axios
                .get(LOGGED_ENDPOINT)
                .then(response => {
                    this.user = response.data.data;
                    console.log(this.user, 1);
                })
                .catch(error => {
                    this.user = null;
                    console.log(this.user, 2);
                })
                .finally(() => {
                    console.log(this.user, 3);
                    this.$emit('user-changed', this.user);
                })

        },
        logOut: function () {
            axios
                .post(LOG_OUT_ENDPOINT)
                .then(response => {
                    window.location.href = '/';
                })
                .catch(error => {
                    console.log(error);
                })

        }
    },
    mounted: function () {
        this.getUser();
    }
}
</script>
