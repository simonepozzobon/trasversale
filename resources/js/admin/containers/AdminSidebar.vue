<template lang="html">
    <nav class="col-md-2 d-none d-md-block sidebar admin-sidebar">
        <div class="sidebar-sticky">
            <h5 class="text-dark pl-3">Pagine</h5>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="#" @click.prevent="$root.goTo('home')">
                        Home <span class="sr-only">(current)</span>
                    </a>
                </li>
                <li
                    class="nav-item"
                    v-for="page in this.$root.pages">
                    <a
                        class="nav-link"
                        :href="'#collapse-'+page.id"
                        data-toggle="collapse"
                        :data-target="'#collapse-'+page.id">

                        {{ page.title }} <span v-if="page.sub_pages.length > 0">+</span>
                    </a>
                    <div :id="'collapse-'+page.id" class="collapse pl-4">
                        <ul class="nav flex-column">
                            <li
                                class="nav-item"
                                v-for="sub in page.sub_pages"
                                :key="sub.id">
                                <a class="nav-link" href="#" @click="goToSub($event, sub)">
                                    {{ sub.title }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
            <div class="divider"></div>
        </div>
    </nav>
</template>

<script>
export default {
    name: 'Sidebar',
    methods: {
        goTo: function(event, route) {
            event.preventDefault()
            this.$router.push(route)
        },
        goToSub: function(event, sub) {
            event.preventDefault()
            let route = { name: 'sub', params: { id: sub.id } }
            console.log(route);
            this.$router.push(route)
        }
    }
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
        margin: $spacer $spacer;
        background-color: rgba($light, .3);
    }

    .nav-link {
        color: $gray-500;
    }
}
</style>
