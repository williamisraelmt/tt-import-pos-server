<template>
<div>
    asdasdsa
</div>
</template>

<script>
export default {
    name: "CatalogDetailComponent",
    mounted() {
    },
    beforeRouteUpdate(to, from, next) {
        console.log(this.$route.params['id']);
    }
}
</script>
