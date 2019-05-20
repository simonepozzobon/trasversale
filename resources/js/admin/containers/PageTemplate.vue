<template lang="html">
    <div class="page-template">
        <edit-panel
            v-if="this.panel"
            :name="this.componentId"/>

        <div class="page-template__container">
            <div class="page-template__head">
                <div class="page-template__title">
                    <h1 class="pt-3">{{ title }}</h1>
                </div>
                <div class="page-template__action">
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
        <b-modal ref="componentSelector" title="Seleziona il componente">
            <div class="form-group">
                <label for="component">Seleziona Componente</label>
                <select name="component" class="form-control" v-model="componentId">
                    <option :value="null">Nessun Componente</option>
                    <option value="title">Titolo</option>
                    <option value="paragraph">Paragrafo</option>
                    <option value="image">Immagine</option>
                    <option value="video">Video</option>
                    <option value="quote">Citazione</option>
                    <option value="grid">Griglia / Colonne</option>
                </select>
            </div>

            <template slot="modal-footer">
                <button class="btn btn-outline-secondary" @click="dismissModal">
                    Chiudi
                </button>
                <button class="btn btn-outline-primary" @click="newComponent">
                    Aggiungi
                </button>
            </template>
        </b-modal>
    </div>
</template>

<script>
import EditPanel from '../components/EditPanel.vue'
import DynamicParams from '../DynamicParams'

export default {
    name: 'PageTemplate',
    components: {
        EditPanel,
    },
    props: {
        title: {
            type: String,
            default: 'titolo'
        }
    },
    data: function() {
        return {
            panel: false,
            component: null,
            componentId: null,
            moduleOpts: DynamicParams,
        }
    },
    methods: {
        debug: function() {
            this.componentId = 'paragraph'
            this.panel = true
        },
        addComponent: function() {
            this.$refs.componentSelector.show()
        },
        dismissModal: function() {
            this.$refs.componentSelector.hide()
        },
        newComponent: function() {
            this.dismissModal()
            this.panel = true
        }
    },
    mounted: function() {
        this.debug()
    }
}
</script>

<style lang="scss">
@import '~styles/adminshared';
$opacity-test: 0.6 !default;

.page-template {
    padding-top: $spacer;
    min-height: 100%;

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
        @include gradient-directional($gray-300, $light, 135deg);
        margin: $spacer * 4;
        padding: $spacer * 2;
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
