<template lang="html">
    <div class="edit-panel">
        <div class="edit-panel__head">
            <div class="edit-panel__title">
                <h1 class="pt-3">Nuovo Componente</h1>
            </div>
        </div>
        <hr>
        <div class="edit-panel__content">
            <dynamic-module
                :name="name"
                :options="module.options"
                :values="values"
                :is-edit="isEdit"
                @changed="setObj"/>
        </div>
        <hr>
        <div class="edit-panel__footer">
            <button
                class="btn btn-outline-primary"
                @click="saveComponent">
                Salva Modifiche
            </button>
            <button
                class="btn btn-outline-secondary"
                @click="undoComponent">
                Annulla
            </button>
            <button
                v-if="isEdit"
                class="btn btn-outline-danger"
                @click="deleteComponent">
                Elimina Componente
            </button>
        </div>
    </div>
</template>

<script>
import DynamicParams from '../DynamicParams'
import DynamicModule from './DynamicModule.vue'

export default {
    name: 'EditPanel',
    components: {
        DynamicModule
    },
    props: {
        name: {
            type: String,
            default: null,
        },
        model: {
            type: String,
            default: null,
        },
        modelIdx: {
            type: Number,
            default: 0,
        },
        moduleId: {
            type: Number,
            default: null,
        },
        values: {
            type: Object,
            default: function() { return {} },
        },
        isEdit: {
            type: Boolean,
            default: false,
        }
    },
    data: function() {
        return {
            obj: null,
        }
    },
    computed: {
        module: function() {
            return DynamicParams.filter(params => params.name == this.name)[0]
        },
    },
    methods: {
        setObj: function(obj) {
            this.obj = obj
        },
        saveComponent: function() {
            let data, url

            if (this.isEdit) {
                data = {
                    id: this.moduleId,
                    model_id: this.modelIdx,
                    model: this.model,
                    module: this.name,
                    data: this.obj
                }

                url = '/api/admin/update-component'
            } else {
                data = {
                    model_id: this.modelIdx,
                    model: this.model,
                    module: this.name,
                    data: this.obj
                }

                url = '/api/admin/save-component'
            }

            this.$http.post(url, data).then(response => {
                if (response.data.success) {
                    this.$emit('saved', response.data.module)
                }
            })
        },
        undoComponent: function() {
            this.$emit('undo')
        },
        deleteComponent: function() {
            let url = '/api/admin/delete-component/' + this.moduleId
            this.$http.delete(url).then(response => {
                // console.log(response.data);
                if (response.data.success) {
                    this.$emit('deleted', response.data.module)
                }
            })
        }
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.edit-panel {
    @include gradient-directional($gray-300, $light, 135deg);
    margin: $spacer * 4;
    padding: $spacer * 2;
    @include border-radius($spacer / 2);
    @include box-shadows($gray-500);

    &__head {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    &__footer {
        display: flex;
        justify-content: space-around;
        align-items: center;
    }
}
</style>
