<template>
    <div>
        <button class="btn btn-primary ml-1" @click="syncWithOdoo()"
                v-bind:disabled="syncing"> {{
                !syncing ? 'Sincronizar con Odoo' :
                    'Sincronizando...'
            }}
        </button>

        <vue-confirm-dialog></vue-confirm-dialog>
    </div>
</template>

<script>

import {CONSTS} from '../consts';
import {isNull} from 'lodash';

const SYNC_ENDPOINT = CONSTS.HOST + 'sync';

export default {
    data: function () {
        return {
            syncing: false,
            synced: false,
            syncState: null
        }
    },
    methods: {
        // checkPendingSync: function () {
        //     axios
        //         .post(`${SYNC_ENDPOINT}/pending`, {})
        //         .then(response => {
        //
        //             const pending = response.data.data;
        //
        //             if (pending) {
        //
        //                 this.syncing = true;
        //
        //                 this.getSyncLatestStatus();
        //
        //             }
        //
        //         })
        //         .catch(error => {
        //
        //             this.$confirm(
        //                 {
        //                     title: 'Información',
        //                     message: 'Hubo un error al sincronizar!',
        //                     button: {
        //                         yes: 'OK',
        //                     }
        //                 });
        //
        //             this.errored = true;
        //
        //             this.syncing = false;
        //         })
        // },
        syncWithOdoo: function () {
            this.syncing = true;
            axios
                .post(SYNC_ENDPOINT, {})
                .then(response => {
                    this.syncing = true;
                    this.getSyncLatestStatus();
                })
                .catch(error => {

                    this.$confirm(
                        {
                            title: 'Información',
                            message: 'Hubo un error al sincronizar!',
                            button: {
                                yes: 'OK',
                            }
                        });

                    this.errored = true;

                    this.syncing = false
                })
        },
        getSyncLatestStatus() {
            axios
                .get(`${SYNC_ENDPOINT}/latest`)
                .then(response => {

                    const data = response.data.data;

                    if (isNull(data)){

                        this.syncing = false;

                        return;
                    }

                    const state = data['state'];

                    if (state === 'IN_PROGRESS') {

                        this.syncing = true;

                        setTimeout(() => {
                            this.getSyncLatestStatus()
                        }, 1000);

                    } else if (state === 'SUCCEED') {

                        this.syncing = false;

                        this.$confirm(
                            {
                                title: 'Información',
                                message: 'La sincronización ha finalizado satisfactoriamente!',
                                button: {
                                    yes: 'OK',
                                }
                            });

                        this.$emit('synced');

                    } else {

                        this.syncing = false;

                        this.$confirm(
                            {
                                title: 'Información',
                                message: 'Hubo un error al sincronizar!',
                                button: {
                                    yes: 'OK',
                                }
                            });

                        this.$emit('synced');
                    }


                })
                .catch(error => {

                    this.$confirm(
                        {
                            title: 'Información',
                            message: 'Hubo un error al sincronizar!',
                            button: {
                                yes: 'OK',
                            }
                        });

                    this.errored = true

                })
        }
    },
    mounted: function () {
        this.getSyncLatestStatus();
    }
}
</script>

<style scoped>

</style>
