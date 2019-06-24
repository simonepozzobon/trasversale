<template>
<li
    v-if="type"
    class="nav-item"
>
    <a
        ref="item"
        class="nav-link nav-item__link"
        :href="'/' + page.slug.slug"
        @click.prevent="$root.goToWithParams('page', {page: page.slug.slug})"
    >
        {{ page.title }}
    </a>
</li>
<li
    v-else
    class="nav-item dropdown dropdown-custom"
    :class="dropdownStatus"
    @mouseover="showDropdown"
    @mouseleave="hideDropdown"
>
    <a
        class="nav-link dropdown-toggle nav-item__link dropdown-custom__toggle"
        ref="item"
        href="#"
        :id="page.slug.slug"
        role="button"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
        @click.prevent="$root.goToWithParams('page', { page: page.slug.slug})"
    >
        {{ page.title }}
    </a>
    <div
        class="dropdown-menu dropdown-custom__menu"
        :class="dropdownStatus"
        :aria-labelledby="page.slug.slug"
    >
        <dropdown-subitem
            v-for="subpage in page.sub_pages"
            :key="subpage.id"
            :title="subpage.title"
            :slug="subpage.slug.slug"
            :page-slug="page.slug.slug"
        />
    </div>
</li>
</template>

<script>
import DropdownSubitem from './DropDownSubItem.vue'
export default {
    name: 'MenuItem',
    components: {
        DropdownSubitem,
    },
    props: {
        page: {
            type: Object,
            default: function () {}
        }
    },
    data: function () {
        return {
            dropdownStatus: null,
        }
    },
    watch: {
        '$route.params': function (route) {
            this.$nextTick(() => {
                this.setActive()
            })
        }
    },
    computed: {
        type: function () {
            if (this.page.hasOwnProperty('sub_pages')) {
                if (this.page.sub_pages.length > 0) {
                    return false
                }
                else {
                    return true
                }
            }
        }
    },
    methods: {
        setActive: function () {
            let params = this.$route.params
            if (params.hasOwnProperty('page')) {
                if (params.page === this.page.slug.slug) {
                    this.$refs.item.classList.add('active')
                }
                else {
                    this.$refs.item.classList.remove('active')
                }
            }
        },
        showDropdown: function () {
            this.dropdownStatus = 'show'
        },
        hideDropdown: function () {
            this.dropdownStatus = null
        }
    },
    mounted: function () {
        this.$nextTick(() => {
            this.setActive()
        })
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
