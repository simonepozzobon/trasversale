<template lang="html">
    <div
        class="column-item"
        :class="colClass">
        <div class="column-item__container">
            <button class="btn btn-outline-primary">
                Aggiungi Componente
            </button>
            <components-list
                ref="components"
                @new-component="newComponent"/>
        </div>
    </div>
</template>

<script>
import ComponentsList from '../ComponentsList.vue'

export default {
    name: 'ColumnItem',
    components: {
        ComponentsList,
    },
    props: {
        idx: {
            type: Number,
            default: 0,
        },
        size: {
            type: Number,
            default: 6,
        },
    },
    data: function() {
        return {
            moduleType: null,
        }
    },
    computed: {
        colClass: function() {
            return 'col-md-' + this.size
        },
    },
    methods: {
        newComponent: function(type) {
            this.moduleType = type
        }
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.column-item {
    &__container {
        margin-top: $spacer;
        padding: $spacer;
        background-color: rgba($white, .5);
        @include border-radius(10px);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
}
</style>
