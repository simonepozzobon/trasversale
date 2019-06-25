<template>
<div class="map-module">
    <div class="form-group row">
        <div class="col-12">
            <h5>Zoom e Posizione iniziale</h5>
            <hr />
        </div>
    </div>
    <div class="form-group row">
        <label
            for="zoom"
            class="col-md-3"
        >
            Zoom iniziale
        </label>
        <div class="col-md-9">
            <input
                type="text"
                name="zoom"
                class="form-control"
                v-model="zoom"
            >
        </div>
    </div>
    <div class="form-group row">
        <label
            for="centerlng"
            class="col-md-3"
        >
            Latitudine
        </label>
        <div class="col-md-9">
            <input
                type="text"
                name="centerlng"
                class="form-control"
                v-model="center.lat"
            >
        </div>
    </div>
    <div class="form-group row">
        <label
            for="centerlat"
            class="col-md-3"
        >
            Longitudine
        </label>
        <div class="col-md-9">
            <input
                type="text"
                name="centerlat"
                class="form-control"
                v-model="center.lat"
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
                :zoom.sync="zoom"
                :lat.sync="center.lat"
                :lng.sync="center.lng"
            />
        </div>
    </div>
    <div class="form-group row mt-5">
        <div class="col-12">
            <h5>Aggiungi Indirizzo</h5>
            <hr />
        </div>
    </div>
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
                :lat.sync="lat"
                :lng.sync="lng"
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
            <button
                class="btn btn-outline-primary"
                @click="addAddress"
            >
                Aggiungi Indirizzo
            </button>
        </div>
    </div>
    <div
        class="form-group row"
        v-if="addresses.length > 0"
    >
        <div class="col-12">
            <map-table :addresses.sync="addresses" />
        </div>
    </div>
</div>
</template>

<script>
import {
    Uuid
}
from '../../../Utilities'
import MapSelect from './MapSelect.vue'
import MapTable from './MapTable.vue'
export default {
    name: 'MapModule',
    components: {
        MapSelect,
        MapTable,
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
            zoom: 13,
            center: {
                lat: 45.461118675626096,
                lng: 9.19131164549529,
            },
        }
    },
    watch: {
        addresses: function (addresses) {
            this.$emit('update', this.adminObj)
        },
        zoom: function (zoom) {
            this.$emit('update', this.adminObj)
        },
        center: {
            handler: function (center) {
                // console.log(this.adminObj);
                this.$emit('update', this.adminObj)
            },
            deep: true,
        }
    },
    computed: {
        adminObj: function () {
            return {
                zoom: this.zoom,
                center: [this.center.lng, this.center.lat],
                addresses: this.addresses
            }
        },
    },
    methods: {
        debug: function () {
            let newAddresses = [{
                    title: 'ristoro 1',
                    lng: 9.201697558164597,
                    lat: 45.45779634687872,
                    link: encodeURI('https://www.google.com/maps/search/?api=1&query=45.45779634687872,9.201697558164597'),
                    uuid: Uuid.get(),
                },
                {
                    title: 'ristoro 2',
                    lng: 9.202320725341453,
                    lat: 45.46842390006836,
                    link: encodeURI('https://www.google.com/maps/search/?api=1&query=45.46842390006836,9.202320725341453'),
                    uuid: Uuid.get(),
                },
            ]

            for (let i = 0; i < newAddresses.length; i++) {
                this.addresses.push(newAddresses[i])
            }
        },
        resetForm: function () {
            this.title = null
            this.lng = 0
            this.lat = 0
        },
        addAddress: function () {
            let link = encodeURI('https://www.google.com/maps/search/?api=1&query=' + this.lat + ',' + this.lng)
            let newAddress = {
                title: this.title,
                lng: this.lng,
                lat: this.lat,
                link: link,
                uuid: Uuid.get(),
            }

            this.addresses.push(newAddress)
            this.resetForm()
        },
    },
    mounted: function () {
        // this.$nextTick(() => this.debug())
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
