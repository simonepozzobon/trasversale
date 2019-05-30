<template lang="html">
    <div class="page-template">
        <div class="page-template__header container">
            <div class="page-template__head">
                <div class="page-template__title">
                    <h1 class="pt-3">{{ title }}</h1>
                </div>
                <div class="page-template__action">
                    <!-- <button
                        class="btn btn-outline-secondary"
                        @click="addRow">
                        Aggiungi Riga
                    </button> -->
                    <button
                        class="btn btn-outline-primary"
                        @click="addComponent">
                        Aggiungi Componente
                    </button>
                </div>
            </div>
        </div>
        <div class="page-template__container container">
            <module-container
                v-for="(module, i) in modules"
                :key="i"
                :item="module"
                :model="model"
                :model-idx="modelIdx"/>
        </div>
        <div class="page-template__footer container">
            <button
                class="btn btn-outline-primary"
                @click="addComponent">
                Aggiungi Componente
            </button>
        </div>
        <components-list
            ref="componentSelector"
            @new-component="newComponent"/>
    </div>
</template>

<script>
import ComponentsList from '../components/ComponentsList.vue'
import DynamicParams from '../DynamicParams'
import EditPanel from '../components/EditPanel.vue'
import ModuleContainer from './ModuleContainer.vue'
import {
    Uuid
} from '../../Utilities'

export default {
    name: 'PageTemplate',
    components: {
        ComponentsList,
        EditPanel,
        ModuleContainer,
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
        modules: {
            type: Array,
            default: function() {
                return []
            },
        },
    },
    data: function() {
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
        '$route.params': function(params) {
            console.log('params cambiato', params);
        },
        modules: function(modules) {
            if (modules.length == 0) {
                this.debug()
            }
        }
    },
    methods: {
        debug: function() {
            // this.moduleType = 'row'
            // this.panel = true
            this.newComponent('grid')
        },
        addRow: function() {
            // console.log('aggiungi');
            let opts = {
                columns: 2,
            }

            let newRow = {
                type: 'row',
                isNew: true,
                modulable_id: this.modelIdx,
                modulable_type: this.model,
                content: null,
            }

            this.modules.push(newRow)
        },
        addComponent: function() {
            this.$refs.componentSelector.show()
        },
        dismissModal: function() {
            this.$refs.componentSelector.hide()
        },
        newComponent: function(type) {
            this.moduleType = type
            this.dismissModal()

            this.isEdit = false
            // this.panel = true
            let newModule = {
                type: type,
                isNew: true,
                modulable_id: this.modelIdx,
                modulable_type: this.model,
                content: JSON.stringify({}),
            }
            this.modules.push(newModule)
        },
        setModule: function(module) {
            console.log('deprecata');

            // // console.log(module);
            // this.isEdit = true
            //
            // this.module = module
            // this.moduleId = module.id
            // this.moduleType = module.type
            // this.values = JSON.parse(module.content)
            // // console.log(this.values);
            // this.panel = true

            // this.debug()
        },
        saved: function(module) {
            this.reset()

            this.$emit('saved', module)
        },
        undo: function() {
            this.reset()
        },
        deleted: function(module) {
            this.reset()
            this.$emit('deleted', module)
        },
        reset: function() {
            this.panel = false

            this.module = null
            this.moduleId = null
            this.moduleType = null
        }
    },
    mounted: function() {
        // this.debug()
        // this.reset()
        // this.$nextTick(this.debug)
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

    background: linear-gradient(45deg, rgba($green, $opacity-test), rgba($teal, $opacity-test), rgba($cyan, $opacity-test), rgba($blue, $opacity-test), rgba($indigo, $opacity-test), rgba($purple, $opacity-test), rgba($pink, $opacity-test), rgba($red, $opacity-test), rgba($orange, $opacity-test), rgba($yellow, $opacity-test),);
    background-size: 800% 800%;
    animation: Gradient 360s ease infinite;

    &__container {
        margin: $spacer * 4;
        padding: $spacer * 2;
        @include gradient-directional($gray-300, $light, 135deg);
        @include border-radius($spacer / 2);
        @include box-shadows($gray-500);
    }

    &__header {
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
        margin-bottom: $spacer * 4;
        margin-left: $spacer * 4;
        margin-right: $spacer * 4;
        padding: $spacer * 2;
        @include gradient-directional($gray-300, $light, 135deg);
        @include border-radius($spacer / 2);
        @include box-shadows($gray-500);
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
