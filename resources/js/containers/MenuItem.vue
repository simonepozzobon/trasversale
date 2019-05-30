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
    <li
        v-else
        class="nav-item dropdown dropdown-custom"
        :class="dropdownStatus"
        @mouseover="showDropdown"
        @mouseleave="hideDropdown">
        <a class="nav-link dropdown-toggle nav-item__link dropdown-custom__toggle"
            href="#"
            :id="page.slug.slug"
            role="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
            @click.prevent="$root.goToWithParams('page', { page: page.slug.slug})">
            {{ page.title }}
        </a>
        <div
            class="dropdown-menu dropdown-custom__menu"
            :class="dropdownStatus"
            :aria-labelledby="page.slug.slug">
            <a
                v-for="subpage in page.sub_pages"
                class="dropdown-item dropdown-custom__item"
                :href="'/' + page.slug.slug + '/' + subpage.slug.slug"
                @click.prevent="$root.goToWithParams('subpage', { page: page.slug.slug, subpage: subpage.slug.slug })">
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
    data: function() {
        return {
            dropdownStatus: null,
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
    },
    methods: {
        showDropdown: function() {
            this.dropdownStatus = 'show'
        },
        hideDropdown: function() {
            this.dropdownStatus = null
        }
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.nav-item {
    &__link {
        font-weight: 700;
        font-family: $font-family-sans-serif-var;
        letter-spacing: 0.05em;
        padding-right: $spacer !important;
        padding-left: $spacer !important;
    }

    &__link:first-child {
        padding-left: 0 !important;
    }
}

.dropdown-custom {
    &__menu {
        margin-top: -1px;
        padding: $spacer !important;
    }

    &__menu::after {
        content: '';
        position: absolute;
        width: 0;
        height: 0;
        top: -($spacer * .9);
        border-left: $spacer solid transparent;
        border-bottom: $spacer solid $primary;
    }

    &__item {
        font-family: $font-family-sans-serif-var;
        font-size: $font-size-base * 0.8;
        letter-spacing: 0.05em;
        padding: 0;
        transition: $transition-base;
    }

    &__item:hover {
        color: $black;
        transition: $transition-base;
    }
}
</style>
