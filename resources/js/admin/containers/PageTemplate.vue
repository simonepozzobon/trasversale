<template>
<div class="page-template">
    <notifications-container :toasts="notifications" />
    <div class="page-template__container container">
        <div class="page-template__head">
            <div class="page-template__title">
                <h1 class="pt-3">{{ title }}</h1>
            </div>
        </div>
    </div>
    <div
        class="page-template__container container"
        v-if="hasSubHeader"
    >
        <slot name="sub-header"></slot>
    </div>
    <div class="page-template__container container">
        <slot></slot>
    </div>
</div>
</template>

<script>
import {
    Uuid
}
from '../../Utilities'
import ComponentsList from '../components/ComponentsList.vue'
import DynamicParams from '../DynamicParams'
import EditPanel from '../components/EditPanel.vue'
import NewModule from '../components/NewModule.vue'
import NotificationsContainer from '../containers/NotificationsContainer.vue'

export default {
    name: 'PageTemplate',
    components: {
        ComponentsList,
        EditPanel,
        NewModule,
        NotificationsContainer,
    },
    props: {
        title: {
            type: String,
            default: 'titolo'
        },
        model: {
            type: String,
            default: null,
        },
        modelIdx: {
            type: Number,
            default: 0,
        },
        notifications: {
            type: Array,
            default: function () {
                return []
            },
        },
        hasSubHeader: {
            type: Boolean,
            default: false,
        }
    },
    data: function () {
        return {
            panel: false,
            component: null,
            moduleType: null,
            moduleOpts: DynamicParams,
            isEdit: false,
            values: null,
            module: null,
            moduleId: null,
            cached: [],
        }
    },
    watch: {
        '$route.params': function (params) {
            // console.log('params cambiato', params);
        },

    },
    methods: {
        debug: function () {
            this.moduleType = 'row'
            this.panel = true
        },
        addRow: function () {
            let newRow = {
                uuid: Uuid.get(),
                moduleType: 'row',
                model: this.model,
                modelIdx: this.modelIdx,
                isEdit: false,
                moduleId: this.moduleId,
                values: [],
            }

            this.cached.push(newRow)
        },
        addComponent: function () {
            this.$refs.componentSelector.show()
        },
        dismissModal: function () {
            this.$refs.componentSelector.hide()
        },
        newComponent: function (type) {
            this.moduleType = type
            this.dismissModal()

            this.isEdit = false
            this.panel = true
        },
        setModule: function (module) {
            // console.log(module);
            this.isEdit = true

            this.module = module
            this.moduleId = module.id
            this.moduleType = module.type
            this.values = JSON.parse(module.content)
            // console.log(this.values);
            this.panel = true
        },
        saved: function (module) {
            this.reset()

            this.$emit('saved', module)
        },
        undo: function () {
            this.reset()
        },
        deleted: function (module) {
            this.reset()
            this.$emit('deleted', module)
        },
        reset: function () {
            this.panel = false

            this.module = null
            this.moduleId = null
            this.moduleType = null
        }
    },
    mounted: function () {
        // this.debug()
        // this.reset()
    },
}
</script>

<style lang="scss">
@import '~styles/adminshared';
$opacity-test: 0.6 !default;

.page-template {
    padding: $spacer * 2;
    min-height: 100vh;
    max-width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    @include gradient-directional($gray-300, $light, 135deg);

    // background: linear-gradient(45deg, rgba($green, $opacity-test), rgba($teal, $opacity-test), rgba($cyan, $opacity-test), rgba($blue, $opacity-test), rgba($indigo, $opacity-test), rgba($purple, $opacity-test), rgba($pink, $opacity-test), rgba($red, $opacity-test), rgba($orange, $opacity-test), rgba($yellow, $opacity-test),);
    // background-size: 300% 300%;
    // animation: Gradient 360s ease infinite;

    &__container {
        margin-top: $spacer * 4;
        margin-left: $spacer * 4;
        margin-right: $spacer * 4;
        padding: $spacer * 2;
        @include gradient-directional($gray-300, $light, 135deg);
        @include border-radius($spacer / 2);
        @include box-shadows($gray-500);
    }

    &__head {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    &__footer {
        display: flex;
        justify-content: center;
        align-items: center;
    }
}

@keyframes Gradient {
    0% {
        background-position: 0 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0 50%;
    }
}
</style>
