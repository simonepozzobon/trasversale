<template lang="html">
    <router-view></router-view>
</template>

<script>
export default {
    name: 'Home',
    data: function() {
        return {
            name: null,
            modules: null,
        }
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
