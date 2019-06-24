<template lang="html">
    <a
        ref="item"
        class="dropdown-item dropdown-custom__item"
        :href="href"
        @click.prevent="$root.goToWithParams('subpage', { page: this.pageSlug, subpage: this.slug })"
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
    // watch: {
    //     slug: function (slug) {
    //         this.setActive()
    //     }
    // },
    methods: {
        // setActive: function () {
        //     let params = this.$route.params
        //     if (params.hasOwnProperty('subpage')) {
        //         if (params.subpage === this.slug) {
        //             this.$refs.item.classList.add('active')
        //         }
        //         else {
        //             this.$refs.item.classList.remove('active')
        //         }
        //     }
        // }
    },
    // mounted: function () {
    //     this.setActive()
    // }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
