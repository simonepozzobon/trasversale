<template>
<div class="ui-map">
    <vl-map
        :load-tiles-while-animating="true"
        :load-tiles-while-interacting="true"
        data-projection="EPSG:4326"
    >
        <vl-view
            :zoom.sync="zoomCache"
            :center.sync="centerCache"
            :rotation.sync="rotation"
        />

        <vl-layer-tile id="osm">
            <vl-source-osm />
        </vl-layer-tile>

        <vl-feature
            v-for="address in addresses"
            :key="address.uuid"
        >

            <vl-geom-point :coordinates="[parseFloat(address.lng), parseFloat(address.lat)]"></vl-geom-point>
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
    name: 'UiMap',
    props: {
        zoom: {
            type: Number,
            default: 13,
        },
        center: {
            type: Array,
            default: function () {
                return [0, 0]
            },
        },
        addresses: {
            type: Array,
            default: function () {
                return []
            },
        },
    },
    data: function () {
        return {
            rotation: 0,
            coordinates: [0, 0],
            zoomCache: 13,
            centerCache: [0, 0],
        }
    },
    watch: {
        zoom: function (zoom) {
            this.zoomCache = zoom
        },
        center: function (center) {
            this.centerCache = center
            console.log('center', center);
        },
    },
    mounted: function () {
        this.zoomCache = this.zoom
        this.centerCache = this.center
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
