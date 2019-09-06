<template>
<div class=" resume">
    <div class="resume__title">
        <ui-title
            title="Riepilogo"
            :is-column="true"
            tag="h4"
            font-size="h4"
            color="gray"
        />
    </div>
    <div class="resume__content">
        <div class="resume__items">
            <div
                class="resume__item"
                v-for="item in items"
                :key="item.uuid"
            >
                <cart-item
                    :item.sync="item.item"
                    :quantity="item.item.quantity"
                />
            </div>
        </div>
        <div class="resume__total">
            <cart-item
                :item="totalItem"
                :has-animation="false"
                :has-border="false"
            />
        </div>
    </div>
</div>
</template>

<script>
import CartItem from './CartItem.vue'
import {
    UiBlock,
    UiButton,
    UiRow,
    UiTitle,
}
from '../ui'
export default {
    name: 'CartResume',
    components: {
        CartItem,
        UiBlock,
        UiButton,
        UiRow,
        UiTitle,
    },
    props: {
        items: {
            type: Array,
            default: function () {
                return []
            },
        },
        totalItem: {
            type: Object,
            default: function () {
                return {}
            },
        },
    },
    data: function () {
        return {
            formatted: []
        }
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
.resume {
    padding: $spacer * 2;
    height: 100%;

    &__content {
        height: 100%;
        padding-top: $spacer * 2;
        padding-bottom: $spacer;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
}
</style>
