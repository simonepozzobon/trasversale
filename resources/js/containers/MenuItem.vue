<template lang="html">
    <li class="nav-item"
        v-if="type">
        <a
            class="nav-link nav-item__link"
            :href="'/' + page.slug.slug"
            @click.prevent="$root.goToWithParams('page', { page: page.slug.slug})">
            {{ page.title }}
        </a>
    </li>
    <li class="nav-item dropdown" v-else>
        <a class="nav-link nav-item__link dropdown-toggle"
            href="#"
            id="navbarDropdown"
            role="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">
            {{ page.title }}
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a
                v-for="subpage in page.sub_pages"
                class="dropdown-item"
                :href="'/' + page.slug.slug + '/' + subpage.slug.slug"
                @click.prevent="$root.goToWithParams('subpage', { subpage: page.slug.slug })">
                {{ subpage.title }}
            </a>
        </div>
    </li>
</template>

<script>
export default {
    name: 'MenuItem',
    props: {
        page: {
            type: Object,
            default: function() {}
        }
    },
    computed: {
        type: function() {
            if (this.page.hasOwnProperty('sub_pages')) {
                if (this.page.sub_pages.length > 0) {
                    return false
                } else {
                    return true
                }
            }
        }
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.nav-item {
    &__link:first-child {
        padding-left: 0;
    }

    &__link {
        font-weight: 500;
    }
}
</style>
