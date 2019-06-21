<template>
<nav class="col-md-2 d-none d-md-block sidebar admin-sidebar">
    <div class="sidebar-sticky">
        <h5 class="text-dark pl-3">Pagine</h5>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a
                    class="nav-link"
                    href="#"
                    @click.prevent="$root.goTo('home')"
                >
                    Admin Home <span class="sr-only">(current)</span>
                </a>
            </li>
            <li
                class="nav-item"
                v-for="page in this.$root.pages"
            >
                <div class="nested-link">
                    <a
                        class="nav-link"
                        href="#"
                        @click.prevent="$root.goToWithParams('page', { page: page.id })"
                    >
                        {{ page.title }}
                    </a>
                    <a
                        class="nav-link nav-link--cross"
                        v-if="page.sub_pages.length > 0"
                        :href="'#collapse-'+page.id"
                        data-toggle="collapse"
                        :data-target="'#collapse-'+page.id"
                    >
                        +
                    </a>
                </div>

                <div
                    :id="'collapse-'+page.id"
                    class="collapse pl-4"
                >
                    <ul class="nav flex-column">
                        <li
                            class="nav-item"
                            v-for="sub in page.sub_pages"
                            :key="sub.id"
                        >
                            <a
                                class="nav-link"
                                href="#"
                                @click.prevent="$root.goToWithParams('sub', { page: page.id, sub: sub.id })"
                            >
                                {{ sub.title }}
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <span class="nav-link">------</span>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link"
                    href="#"
                    @click.prevent="$root.goToWithParams('posts', {type: 'corsi'})"
                >
                    Corsi
                </a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link"
                    href="#"
                    @click.prevent="$root.goToWithParams('posts', {type: 'notizie'})"
                >
                    Notizie
                </a>
            </li>
        </ul>
        <div class="divider"></div>

    </div>
</nav>
</template>

<script>
export default {
    name: 'Sidebar',
    methods: {}
}
</script>

<style lang="scss">
@import '~styles/adminshared';

.admin-sidebar {
    min-height: 100vh;
    @include gradient-directional($gray-300, $light, 135deg);
    @include box-shadows($gray-500);
    z-index: 1;

    .sidebar-sticky {
        padding-top: $spacer * 4.5;
    }

    .divider {
        width: 10%;
        height: 1px;
        margin: $spacer;
        background-color: rgba($light, .3);
    }

    .nested-link {
        display: flex;
    }

    .nav-link {
        color: $gray-500;

        &--cross {
            color: $primary;
            display: inline-block;
            padding-left: 0;
        }
    }
}
</style>
