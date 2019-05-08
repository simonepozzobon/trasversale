<template lang="html">
    <div class="container">
        <main-menu :pages="parsedPages"/>
        <div class="">
            <div class="row">
                <div class="col-md-9">
                    <router-view />
                </div>
                <div class="col-md-3">
                    Sidebar
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import MainMenu from './MainMenu.vue'

export default {
    name: 'MainTemplate',
    components: {
        MainMenu,
    },
    props: {
        pages: {
            type: String,
            default: function() {}
        }
    },
    computed: {
        parsedPages: function() {
            if (this.pages) {
                return JSON.parse(this.pages)
            }
        }
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
