<template lang="html">
    <nav class="navbar navbar-light admin-topbar fixed-top bg-light flex-md-nowrap py-2">
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Trasversale</a>
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="#">Sign out</a>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {
}
</script>

<style lang="scss">
@import '~styles/adminshared';
.admin-topbar {
    @include gradient-directional($light, $gray-300, -175deg);
    @include box-shadows($gray-500);
}
</style>
