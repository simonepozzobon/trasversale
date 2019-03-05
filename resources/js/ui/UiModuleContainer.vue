<template lang="html">
    <div v-if="this.module" class="pb-4">
        <ui-title
            v-if="module.type == 'title'"
            :title="parsed.content"/>

        <ui-image
            v-else-if="module.type == 'image'"
            :src="parsed.src"
            :alt="parsed.alt"/>

        <ui-paragraph
            v-else-if="module.type == 'paragraph'"
            :content="parsed.content"/>

        <ui-button
            v-else-if="module.type == 'button'"
            :text="parsed.text"/>

        <ui-team
            v-else-if="module.type == 'team'"
            :grid-col="parsed.gridCol"
            :people="parsed.people"
            />
    </div>
</template>

<script>
import UiButton from './UiButton.vue'
import UiImage from './UiImage.vue'
import UiParagraph from './UiParagraph.vue'
import UiTeam from './UiTeam.vue'
import UiTitle from './UiTitle.vue'

export default {
    name: 'UiModuleContainer',
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
    computed: {
        parsed: function() {
            if (this.module && this.module.content) {
                return JSON.parse(this.module.content)
            }
            return {}
        }
    }
}
</script>

<style lang="css" scoped>
</style>
