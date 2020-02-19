<template>
<div>
    <module-manager
        v-for="module in this.modules"
        :key="module.id"
        :module="module"
    />
    <div
        v-if="name == 'corsi'"
        class="archive-btn"
    >
        <ui-button
            title="Archivio corsi"
            color="primary"
            align="center"
            @click="$root.goToWithParams('subpage', {page: 'corsi', subpage: 'archivio'})"
        />
    </div>
    <div v-else-if="name == 'notizie'">
        <ui-button
            title="Archivio notizie"
            color="primary"
            align="center"
            @click="$root.goToWithParams('subpage', {page: 'notizie', subpage: 'archivio'})"
        />
    </div>
</div>
</template>

<script>
import ModuleManager from '../containers/ModuleManager.vue'
import {
    sortModules
}
from '../Utilities'
import {
    UiButton
}
from '../ui'

export default {
    name: 'Page',
    components: {
        ModuleManager,
        UiButton,
    },
    data: function () {
        return {
            name: null,
            modules: null,
        }
    },
    watch: {
        '$route': function (v) {
            this.init()
        }
    },
    methods: {
        init: function () {
            let url = '/api/get-page/' + this.$route.params.page
            this.getData(url)
        },
        getData: function (url) {
            if (url) {
                this.$http.get(url).then(response => {
                    console.log(response.data);
                    if (response.data.success) {
                        this.$root.sidebar = response.data.item.sidebar
                        this.name = response.data.item.title
                        this.modules = sortModules(response.data.item.modules)
                        // console.log(this.modules);
                    }
                })
            }
        }
    },
    created: function () {
        this.init()
    },
    mounted: function () {}
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.archive-btn {
    width: 100%;
}
</style>
