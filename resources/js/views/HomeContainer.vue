<template lang="html">
    <div>
        <router-view></router-view>
    </div>
</template>

<script>
export default {
    name: 'HomeContainer',
    data: function () {
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
