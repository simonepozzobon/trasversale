<template lang="html">
    <div class="pb-3">
        <ui-sidebar-title title="Partner Istituzionali"/>
        <ui-partner
            v-for="(item, i) in this.partners"
            :key="item.id"
            :title="item.title"
            :img="item.img"
            :size="50"/>
        <p class="mt-3">
            Trasversale srl<br>
            via della vignola 70<br>
            00196 Roma
        </p>
    </div>
</template>

<script>
import { Partners } from '../contents/Dummies'
import { UiPartner, UiSidebarTitle } from '../ui'

export default {
    name: 'SidebarInformation',
    components: {
        UiPartner,
        UiSidebarTitle,
    },
    data: function() {
        return {
            partners: Partners,
        }
    },
}
</script>

<style lang="css" scoped>
</style>
