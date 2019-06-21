<template lang="html">
    <router-view :key="$route.fullPath"/>
</template>

<script>
export default {
    name: 'PageContainer',
    data: function () {
        return {
            name: null,
            modules: null,
        }
    },
    mounted: function () {
        // console.log('Page Container -> Mounted');
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
