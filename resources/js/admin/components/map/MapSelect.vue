<template>
<div class="map-select">
    <vl-map
        @click="onMapClick"
        :load-tiles-while-animating="true"
        :load-tiles-while-interacting="true"
        data-projection="EPSG:4326"
    >
        <vl-view
            :zoom.sync="zoom"
            :center.sync="center"
            :rotation.sync="rotation"
        />

        <vl-layer-tile id="osm">
            <vl-source-osm />
        </vl-layer-tile>

        <vl-feature
            v-for="(address, i) in addresses"
            :key="address.id"
        >

            <vl-geom-point :coordinates="[parseFloat(this.lng), parseFloat(this.lat)]" />
            <vl-style-box @click.native="showAddressDetails">
                <vl-style-icon
                    src="/svg/pin.svg"
                    :scale="0.2"
                    :anchor="[1, 1]"
                />
            </vl-style-box>
        </vl-feature>

    </vl-map>
</div>
</template>

<script>
export default {
    name: 'MapSelect',
    props: {
        lat: [String, Number],
        lng: [String, Number],
    },
    data: function () {
        return {
            zoom: 13,
            center: [9.19131164549529, 45.461118675626096],
            rotation: 0,
            addresses: [],
        }
    },
    methods: {
        onMapClick: function (event) {
            this.addresses.splice(0, 1)
            this.addresses.push({
                lng: event.coordinate[0],
                lat: event.coordinate[1],
            })
            this.$emit('update', event.coordinate)
        },
        showAddressDetails: function () {
            console.log('show detail');
        }
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
