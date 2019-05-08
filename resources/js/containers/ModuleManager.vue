<template lang="html">
    <div>
        <ui-title
            v-if="module.type == 'title'"
            :title="content.content"/>
        <ui-image
            v-else-if="module.type == 'image'"
            :src="content.src"
            :alt="content.alt"/>
        <ui-paragraph
            v-else-if="module.type == 'paragraph'"
            :content="content.content"/>
        <ui-button
            v-else-if="module.type == 'button'"
            :text="content.text"/>
        <ui-team
            v-else-if="module.type == 'team'"
            :people="content.people"
            :grid-col="content.gridCol"/>
        <div v-else>
            {{ module }}
        </div>
    </div>
</template>

<script>
import { UiButton, UiImage, UiParagraph, UiTitle } from '../ui'
import UiTeam from '../ui/UiTeam.vue'

export default {
    name: 'ModuleManager',
    components: {
        UiButton,
        UiImage,
        UiParagraph,
        UiTeam,
        UiTitle,
    },
    props: {
        module: {
            type: Object,
            default: function() {}
        }
    },
    data: function() {
        return {
        }
    },
    computed: {
        content: function() {
            return JSON.parse(this.module.content)
        }
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
