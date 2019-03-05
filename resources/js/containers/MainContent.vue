<template lang="html">
    <div>
        <ui-module-container
            v-for="item in this.modulesParsed"
            :key="item.id"
            :module="item"/>
    </div>
</template>

<script>
import { Modules } from '../contents/Dummies'
import { UiModuleContainer } from '../ui'
export default {
    name: 'MainContent',
    components: {
        UiModuleContainer,
    },
    props: {
        modules: {
            type: String,
            default: null
        }
    },
    data: function() {
        return {

        }
    },
    computed: {
        modulesParsed: function() {
            if (this.modules) {
                return JSON.parse(this.modules)
            }

            return []
        }
    },
}
</script>

<style lang="css" scoped>
</style>
