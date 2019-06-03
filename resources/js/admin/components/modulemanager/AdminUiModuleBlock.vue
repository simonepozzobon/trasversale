<template>
<div class="ui-block"
    :class="[
        sizeClass,
        transparentClass,
    ]"
    ref="block">

    <div class="ui-block__container"
        ref="container">

        <module-container v-for="(module, i) in modules"
            :key="i"
            :item="module"
            :model-idx="modelIdx" />

        <components-list ref="componentSelector"
            :exclude="['row', 'grid']"
            @new-component="newComponent" />

        <div class="ui-block__action"
            v-if="isOpen">
            <counter @update="updateSize"
                :size="content.size" />
            <button class="btn btn-outline-primary ui-block__add"
                @click="addModule">
                Aggiungi
            </button>
        </div>
    </div>
</div>
</template>

<script>
import Counter from './Counter.vue'
import ModuleManager from '../../../containers/ModuleManager.vue'
import ComponentsList from '../ComponentsList.vue'

export default {
    name: 'AdminUiBlock',
    components: {
        Counter,
        ModuleManager,
        ComponentsList,
    },
    props: {
        column: {
            type: Object,
            default: function () {},
        },
        isOpen: {
            type: Boolean,
            default: false,
        },
        idx: {
            type: Number,
            default: 0,
        }
    },
    data: function () {
        return {
            size: null,
            align: null,
            model: 'App\\Module',
            modelIdx: 0
        }
    },
    computed: {
        content: function () {
            return JSON.parse(this.column.content)
        },
        sizeClass: function () {
            if (this.content.size == 'auto') {
                return 'col'
            }
            return 'col-md-' + this.content.size
        },
        transparentClass: function () {
            if (this.transparent) {
                return 'ui-block--transparent'
            }
        },
        modules: function () {
            return this.column.modules
        }
    },
    methods: {
        updateSize: function (size) {
            this.$emit('update', {
                size: size,
                idx: this.idx
            })
        },
        addModule: function () {
            this.$refs.componentSelector.show()
        },
        newComponent: function (type) {
            console.log(type);
        }
    },
    beforeCreate: function () {
        this.$options.components.ModuleContainer = require('../../containers/ModuleContainer.vue')
            .default
    },
    created: function () {
        this.size = this.content.size
        this.align = this.content.hasOwnProperty('align') ? this.content.align : null
        console.log(this.modules);
    },
    mounted: function () {},
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.ui-block {
    $self: &;
    margin-top: $spacer;
    margin-bottom: $spacer;
    // min-height: 80px;
    // padding: $spacer;
    // background-color: rgba($white, .5);
    // @include border-radius(10px);
    // display: flex;
    // flex-direction: column;
    // justify-content: center;
    // align-items: center;

    &__container {
        background-color: rgba($white, .5);
        @include border-radius(10px);

        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
    }

    &__action {
        margin-top: $spacer;
        margin-bottom: $spacer;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
    }

    &__add {
        margin-left: $spacer;
    }

    &--radius {
        @include border-radius(5px);
    }

    &--radius-md {
        @include border-radius(16px);
    }

    &--radius &__container {
        @include border-radius(5px);
    }

    &--radius-md &__container {
        @include border-radius(16px);
    }

    &--align-start &__container {
        align-items: flex-start;
    }

    &--align-end &__container {
        align-items: flex-end;
    }

    &--flex-row &__container {
        flex-direction: row;
        align-items: center;
    }

    &--flex-row#{&}--align-start &__container {
        justify-content: flex-start;
    }

    &--flex-row#{&}--align-end &__container {
        justify-content: flex-end;
    }

    &--flex-row#{&}--justify-end &__container {
        align-items: flex-end;
    }

    &--flex-row#{&}--justify-center &__container {
        align-items: center;
    }

    &--transparent-red {
        background-color: rgba($red, .8);
    }

    &--transparent-yellow {
        background-color: rgba($yellow, .8);
    }

    &--transparent-green {
        background-color: rgba($green, .8);
    }

    &--full-height &__container {
        height: 100%;
        justify-content: flex-start;
    }
}
</style>
