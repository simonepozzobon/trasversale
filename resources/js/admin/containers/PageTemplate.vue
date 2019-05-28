<template lang="html">
    <div class="page-template">
        <edit-panel
            v-if="this.panel"
            ref="panel"
            :name="this.moduleType"
            :model="model"
            :model-idx="modelIdx"
            :is-edit="isEdit"
            :module-id="moduleId"
            :values="values"
            @saved="saved"
            @undo="undo"
            @deleted="deleted"/>

        <div class="page-template__container container">
            <div class="page-template__head">
                <div class="page-template__title">
                    <h1 class="pt-3">{{ title }}</h1>
                </div>
                <div class="page-template__action">
                    <button
                        class="btn btn-outline-secondary"
                        @click="addRow">
                        Aggiungi Riga
                    </button>
                    <button
                        class="btn btn-outline-primary"
                        @click="addComponent">
                        Aggiungi Componente
                    </button>
                </div>
            </div>
            <hr>
            <slot></slot>
            <hr>
            <div class="page-template__footer">
                <button
                    class="btn btn-outline-primary"
                    @click="addComponent">
                    Aggiungi Componente
                </button>
            </div>
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

export default {
    name: 'PageTemplate',
    components: {
        ComponentsList,
        EditPanel,
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
        }
    },
    watch: {
        '$route.params': function(params) {
            console.log('params cambiato', params);
        },

    },
    methods: {
        debug: function() {
            this.moduleType = 'row'
            this.panel = true
        },
        addRow: function() {
            
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
            this.panel = true
        },
        setModule: function(module) {
            // console.log(module);
            this.isEdit = true

            this.module = module
            this.moduleId = module.id
            this.moduleType = module.type
            this.values = JSON.parse(module.content)
            // console.log(this.values);
            this.panel = true
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
        this.debug()
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

    background: linear-gradient(
        45deg,
        rgba($green, $opacity-test),
        rgba($teal, $opacity-test),
        rgba($cyan, $opacity-test),
        rgba($blue, $opacity-test),
        rgba($indigo, $opacity-test),
        rgba($purple, $opacity-test),
        rgba($pink, $opacity-test),
        rgba($red, $opacity-test),
        rgba($orange, $opacity-test),
        rgba($yellow, $opacity-test),
    );
    background-size: 800% 800%;
    animation: Gradient 360s ease infinite;

    &__container {
        margin: $spacer * 4;
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
		background-position: 0% 50%
	}
	50% {
		background-position: 100% 50%
	}
	100% {
		background-position: 0% 50%
	}
}
</style>
