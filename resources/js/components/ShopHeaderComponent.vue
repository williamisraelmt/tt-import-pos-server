<template>
    <header class="navbar navbar-expand-md navbar-transparent d-print-none">
        <div class="container-xl">
            <!--                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">-->
            <!--                    <span class="navbar-toggler-icon"></span>-->
            <!--                </button>-->
            <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                <a href=".">
                    TT Import App
                </a>
            </h1>
            <div class="navbar-nav flex-row  order-md-last">
                <div class="nav-item d-md-flex">
                    <div class="btn-list">
                        <template v-if="user">
                            <a href="/cart" class="btn btn-success" rel="noreferrer">
                                <!-- Download SVG icon from http://tabler-icons.io/i/heart -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <circle cx="6" cy="19" r="2"/>
                                    <circle cx="17" cy="19" r="2"/>
                                    <path d="M17 17h-11v-14h-2"/>
                                    <path d="M6 5l14 1l-1 7h-13"/>
                                </svg>
                                0 items
                            </a>
                            <user-dropdown-component menu="shop" v-on:user-changed="user = $event"></user-dropdown-component>
                        </template>


                        <a href="/login" class="btn btn-primary" v-if="!user" rel="noreferrer">
                            <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="12" cy="7" r="4"/>
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                            </svg>
                            Iniciar sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>

<script>

export default {
    data: function () {
        return {
            user: {}
        }
    },
    methods: {
    },
    mounted: function () {
    }
}
</script>
