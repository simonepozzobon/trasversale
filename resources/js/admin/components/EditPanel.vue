<template lang="html">
    <div class="edit-panel container">
        <div class="edit-panel__container">
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
        values: [Object, Array],
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
            // console.log(obj);
            this.obj = obj
        },
        saveComponent: function() {
            let request = {
                id: this.moduleId,
                model_id: this.modelIdx,
                model: this.model,
                module: this.name,
                data: this.obj,
            }

            // console.log('save', this.obj, this.isEdit);
            // return false
            let data = this.formatRequest(request)

            this.$http.post('/api/admin/save-component', data).then(response => {
                // console.log(response.data);
                if (response.data.success) {
                    this.$emit('saved', response.data.module)
                }
            })
        },
        formatRequest: function(obj) {
            let form = new FormData()

            // inserisco i campi normali
            for (let key in obj) {
                if (obj.hasOwnProperty(key)) {
                    if (key == 'data') {
                        let content = obj[key]
                        let hasFile = this.hasFile(content)
                        // se nel contenuto del modulo c'è un file
                        if (hasFile) {
                            form.append('file', content[hasFile])
                        }
                        form.append(key, JSON.stringify(content))
                    } else {
                        form.append(key, obj[key])
                    }
                }
            }

            return form
        },
        hasFile: function(obj) {
            // https://stackoverflow.com/questions/31525667/check-if-variable-holds-file-or-blob
            for (let key in obj) {
                if (obj.hasOwnProperty(key) && obj[key] instanceof File) {
                    return key
                }
            }
            return false
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
    margin-top: $spacer * 4;
    padding-left: 0;
    padding-right: 0;
    max-width: 100%;

    &__container {
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
        justify-content: space-around;
        align-items: center;
    }
}
</style>
