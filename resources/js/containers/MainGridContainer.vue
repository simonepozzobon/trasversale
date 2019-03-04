<template lang="html">
    <ui-packery-container
        :items="this.items"
        :gutter="8"
        :units="12"/>
</template>

<script>
import { MainGridPosts } from '../contents/Dummies'
import { UiPackeryContainer } from '../ui'
export default {
    name: 'MainGridContainer',
    components: {
        UiPackeryContainer,
    },
    data: function() {
        return {
            items: MainGridPosts
        }
    }
}
</script>

<style lang="css">
</style>
