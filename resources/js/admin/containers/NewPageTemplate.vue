<template>
<div class="page-template">
    <div class="page-tamplate__container container">
        <div class="page-template__row row">
            <div class="page-template__main col-md-9">
                <div class="page-template__header">
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
                                @click="addComponent"
                            >
                                Aggiungi Componente
                            </button>
                        </div>
                    </div>
                </div>
                <div class="page-template__content">
                    <module-container
                        v-for="(module, i) in cached"
                        :key="i"
                        :item="module"
                        :model="model"
                        :model-idx="modelIdx"
                        @deleted="deleteComponent"
                    />
                </div>
                <div class="page-template__footer">
                    <button
                        class="btn btn-outline-primary"
                        @click="addComponent"
                    >
                        Aggiungi Componente
                    </button>
                </div>
                <components-list
                    ref="componentSelector"
                    @new-component="newComponent"
                />
            </div>
            <div class="page-template__main col-md-3">
                <div class="page-template__header">
                    <div class="page-template__head">
                        <div class="page-template__title">
                            <h1 class="pt-3">Sidebar</h1>
                        </div>
                        <div class="page-template__action">
                            <button
                                class="btn btn-outline-primary"
                                @click="addComponentSide"
                            >
                                Aggiungi Componente
                            </button>
                        </div>
                    </div>
                </div>
                <div class="page-template__content">
                    <module-container
                        v-for="(module, i) in cachedSides"
                        :key="i"
                        :item="module"
                        :model="model"
                        :model-idx="modelIdx"
                        @deleted="deleteComponentSide"
                    />
                </div>
                <div class="page-template__footer">
                    <button
                        class="btn btn-outline-primary"
                        @click="addComponentSide"
                    >
                        Aggiungi Componente
                    </button>
                </div>
                <components-list
                    ref="sidebarSelector"
                    @new-component="newComponentSide"
                />
            </div>
        </div>
    </div>

</div>
</template>

<script>
import ComponentsList from '../components/ComponentsList.vue'
import DynamicParams from '../DynamicParams'
import EditPanel from '../components/EditPanel.vue'
import ModuleContainer from './ModuleContainer.vue'
import {
    Uuid
}
from '../../Utilities'

export default {
    name: 'NewPageTemplate',
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
        sides: {
            type: Array,
            default: function() {
                return []
            },
        },
        hasSidebar: {
            type: Boolean,
            default: false,
        },
    },
    data: function() {
        return {
            component: null,
            moduleType: null,
            moduleOpts: DynamicParams,
            isEdit: false,
            values: null,
            cached: [],
            cachedSides: [],
        }
    },
    computed: {
        contentClass: function() {
            if (this.hasSidebar) {
                return 'col-md-9'
            }
        },
    },
    watch: {
        '$route.params': function(params) {
            console.log('params cambiato', params);
        },
        modules: function(modules) {
            this.init()
        }
    },
    methods: {
        init: function() {
            // imposta una variabile intermedia per poter modificare i moduli
            this.cached = this.modules
        },
        addComponent: function() {
            this.$refs.componentSelector.show()
        },
        newComponent: function(type) {
            this.moduleType = type
            this.dismissModal()

            this.isEdit = false
            const newModule = {
                type: type,
                isNew: true,
                modulable_id: this.modelIdx,
                modulable_type: this.model,
                content: JSON.stringify({}),
            }
            this.cached.push(newModule)
        },
        deleteComponent: function(component) {
            let idx = this.cached.indexOf(component)
            if (idx > -1) {
                this.cached.splice(idx, 1)
            }
        },
        dismissModal: function() {
            this.$refs.componentSelector.hide()
        },

        addComponentSide: function() {
            this.$refs.sidebarSelector.show()
        },
        deleteComponentSide: function(component) {
            let idx = this.cachedSides.indexOf(component)
            if (idx > -1) {
                this.cachedSides.splice(idx, 1)
            }
        },
        dismissModalSide: function() {
            this.$refs.sidebarSelector.hide()
        },
        newComponentSide: function(type) {
            this.sideType = type
            this.dismissModal()

            this.isEdit = false
            const newSide = {
                type: type,
                isNew: true,
                modulable_id: this.modelIdx,
                modulable_type: this.model,
                content: JSON.stringify({}),
            }
            this.cachedSides.push(newSide)
        },
        setModule: function(module) {
            console.log('deprecata');
        },
        deleted: function(module) {
            this.moduleType = null
            this.$emit('deleted', module)
        },
        deletedSide: function(sidebarModule) {
            this.sideType = null
            this.$emit('deleted', sidebarModule)
        },
        // reset: function() {
        //     this.moduleType = null
        //     this.sideType = null
        // }
    },
    mounted: function() {
        this.init()
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

    &__content {
        margin-top: $spacer * 4;
        margin-bottom: $spacer * 4;
        padding: $spacer * 2;
        @include gradient-directional($gray-300, $light, 135deg);
        @include border-radius($spacer / 2);
        @include box-shadows($gray-500);
    }

    &__header {
        margin-top: $spacer * 4;
        padding: $spacer * 2;
        @include gradient-directional($gray-300, $light, 135deg);
        @include border-radius($spacer / 2);
        @include box-shadows($gray-500);
    }

    &__head {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
    }

    &__footer {
        margin-top: $spacer * 4;
        margin-bottom: $spacer * 4;
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
