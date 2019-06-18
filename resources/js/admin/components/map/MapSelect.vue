<template>
<div class="map-select">
    <vl-map
        @click="onMapClick"
        :load-tiles-while-animating="true"
        :load-tiles-while-interacting="true"
        data-projection="EPSG:4326"
    >
        <vl-view
            :zoom.sync="zoomCache"
            :center.sync="center"
            :rotation.sync="rotation"
        />

        <vl-layer-tile id="osm">
            <vl-source-osm />
        </vl-layer-tile>

        <vl-feature>

            <vl-geom-point :coordinates.sync="coordinates"></vl-geom-point>
            <vl-style-box @click.native="showAddressDetails">
                <vl-style-icon
                    src="/svg/pin.svg"
                    :scale="0.2"
                    :anchor="[1, 1]"
                ></vl-style-icon>
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
        zoom: {
            type: Number,
            default: 13
        }
    },
    data: function () {
        return {
            center: [9.19131164549529, 45.461118675626096],
            rotation: 0,
            coordinates: [0, 0],
            zoomCache: 13,
        }
    },
    watch: {
        zoomCache: function (zoom) {
            this.$emit('update:zoom', zoom)
        }
    },
    methods: {
        onMapClick: function (event) {
            let lat = event.coordinate[1]
            let lng = event.coordinate[0]
            this.coordinates = [parseFloat(lng), parseFloat(lat)]
            this.$emit('update:lng', lng)
            this.$emit('update:lat', lat)
        },
        showAddressDetails: function () {
            console.log('show detail');
        }
    },
    created: function () {
        this.zoomCache = this.zoom
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
