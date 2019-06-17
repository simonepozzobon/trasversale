<template>
<div class="map-module">
    <div class="form-group row">
        <label
            for="title"
            class="col-md-3"
        >
            Titolo
        </label>
        <div class="col-md-9">
            <input
                type="text"
                name="title"
                class="form-control"
                v-model="title"
            >
        </div>
    </div>
    <div class="form-group row">
        <label
            for="lat"
            class="col-md-3"
        >
            Latitudine
        </label>
        <div class="col-md-9">
            <input
                type="text"
                name="lat"
                class="form-control"
                v-model="lat"
            >
        </div>
    </div>
    <div class="form-group row">
        <label
            for="lng"
            class="col-md-3"
        >
            Longitudine
        </label>
        <div class="col-md-9">
            <input
                type="text"
                name="lng"
                class="form-control"
                v-model="lng"
            >
        </div>
    </div>
    <div class="form-group row">
        <label
            for="lng"
            class="col-md-3"
        >
            Mappa
        </label>
        <div class="col-md-9">
            <map-select
                :sync.lat="lat"
                :sync.lng="lng"
                @update="updateMap"
            />
        </div>
    </div>
    <div class="form-group row">
        <label
            for="lng"
            class="col-md-3"
        >
            Aggiungi
        </label>
        <div class="col-md-9">
            <button class="btn btn-outline-primary">Aggiungi Indirizzo</button>
        </div>
    </div>
</div>
</template>

<script>
import MapSelect from './MapSelect.vue'
export default {
    name: 'MapModule',
    components: {
        MapSelect
    },
    props: {
        label: {
            type: String,
            default: null,
        },
        name: {
            type: String,
            default: null,
        },
        info: {
            type: String,
            default: null,
        },
    },
    data: function () {
        return {
            addresses: [],
            lat: 0,
            lng: 0,
            title: null,
        }
    },
    methods: {
        updateMap: function (coordinates) {
            this.lng = coordinates[0]
            this.lat = coordinates[1]
        }
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
