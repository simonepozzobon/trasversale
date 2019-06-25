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
    <div class="ui-map__addresses">
        <div
            class="ui-map__address ui-map-address"
            v-for="address in addresses"
            :key="address.uuid"
        >
            <h6 class="ui-map-address__title">{{ address.title }}</h6> -
            <a
                :href="address.link"
                class="ui-map-address__link"
                target="_blank"
            >
                Link Google Maps
            </a>
        </div>
    </div>
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
                return [9.19131164549529, 45.461118675626096]
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

.ui-map {
    &__addresses {
        margin-top: $spacer;
    }

    .ui-map-address {
        &__title {
            display: inline-block;
        }

        &__link {}
    }
}
</style>
