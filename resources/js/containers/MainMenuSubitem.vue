<template lang="html">
    <a
        ref="item"
        class="nav-link main-submenu__link"
        :href="href"
        @click.prevent="subClicked"
    >
        {{ title }}
    </a>
</template>

<script>
export default {
    name: 'DropdownSubitem',
    props: {
        pageSlug: {
            type: String,
            default: '',
        },
        title: {
            type: String,
            default: 'titolo',
        },
        slug: {
            type: String,
            default: '',
        },
    },
    computed: {
        href: function () {
            return '/' + this.pageSlug + '/' + this.slug
        },
    },
    watch: {
        '$route.params': function (params) {
            this.$nextTick(() => this.setActive())
        }
    },
    methods: {
        subClicked: function () {
            this.$root.goToWithParams('subpage', {
                page: this.pageSlug,
                subpage: this.slug,
            })
        },
        setActive: function () {
            let params = this.$route.params
            if (params.hasOwnProperty('subpage')) {
                if (params.subpage === this.slug) {
                    this.$refs.item.classList.add('active')
                }
                else {
                    this.$refs.item.classList.remove('active')
                }
            }
        }
    },
    mounted: function () {
        this.setActive()
    }
}
</script>

<style lang="scss">
@import '~styles/shared';
</style>
