<template>
<nav class="navbar navbar-light admin-topbar fixed-top bg-light py-2">
    <a
        class="navbar-brand col-sm-3 col-md-2 mr-0"
        href="#"
    >
        Trasversale
    </a>
    <div class="navbar-nav">
        <a
            href="#"
            @click.prevent="$root.toggleSide()"
            class="nav-item nav-link"
        >
            {{ menuTxt }}
        </a>
    </div>
    <ul class="navbar-nav ml-auto px-3">
        <li class="nav-item text-nowrap">
            <a
                class="nav-link"
                href="#"
                @click.prevent.stop="$root.logout"
            >
                Sign out
            </a>
        </li>
    </ul>
</nav>
</template>

<script>
export default {
    name: 'TopBar',
    data: function () {
        return {
            menuTxt: 'Mostra Menu',
            isOpen: false,
        }
    },
    watch: {
        '$root.sideIsOpen': function (value) {
            this.setMenu(value)
        }
    },
    methods: {
        setMenu: function (value) {
            if (value) {
                this.menuTxt = 'Nascondi Menu'
            }
            else {
                this.menuTxt = 'Mostra Menu'
            }
        }
    },
    mounted: function () {
        this.$nextTick(() => {
            this.setMenu(this.$root.sideIsOpen)
        })
    },
}
</script>

<style lang="scss">
@import '~styles/adminshared';
.admin-topbar {
    @include gradient-directional($light, $gray-300, -175deg);
    @include box-shadows($gray-500);
}
</style>
