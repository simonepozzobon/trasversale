<template>
<div class="page-template">
    <div class="page-tamplate__container container">
        <div class="page-template__row row">
            <div class="page-template__main col-12">
                <div class="page-template__header">
                    <div class="page-template__head">
                        <div class="page-template__title">
                            <h1 class="pt-3">{{ title }}</h1>
                        </div>
                        <div class="page-template__action">
                            <button
                                class="btn btn-outline-primary"
                                @click="addComponent"
                            >
                                Aggiungi Componente
                            </button>
                            <button
                                class="btn btn-outline-success"
                                @click="savePage"
                            >
                                Salva Pagina
                            </button>
                        </div>
                    </div>
                </div>
                <div class="page-template__content">
                    <draggable
                        v-model="cached"
                        @update="sortModules"
                    >
                        <module-container
                            v-for="(module, i) in cached"
                            :key="module.uuid"
                            :item="module"
                            :model="model"
                            :model-idx="modelIdx"
                            @deleted="deletedComponent"
                            @save="saveComponent"
                            @delete="deleteComponent"
                            @save-sub-module="saveSubModule"
                        />
                    </draggable>
                </div>
                <div class="page-template__footer">
                    <button
                        class="btn btn-outline-primary"
                        @click="addComponent"
                    >
                        Aggiungi Componente
                    </button>
                    <button
                        class="btn btn-outline-success ml-2"
                        @click="savePage"
                    >
                        Salva Pagina
                    </button>
                </div>
                <components-list
                    ref="componentSelector"
                    @new-component="newComponent"
                />
            </div>
        </div>
    </div>

</div>
</template>

<script>
import ComponentsList from '../components/ComponentsList.vue'
import draggable from 'vuedraggable'
import DynamicParams from '../DynamicParams'
import EditPanel from '../components/EditPanel.vue'
import ModuleContainer from './ModuleContainer.vue'
import {
    Uuid
}
from '../../Utilities'

const orderBy = require('lodash.orderby')

export default {
    name: 'NewPageTemplate',
    components: {
        ComponentsList,
        draggable,
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
            default: function () {
                return []
            },
        },
        sides: {
            type: Array,
            default: function () {
                return []
            },
        },
        hasSidebar: {
            type: Boolean,
            default: false,
        },
    },
    data: function () {
        return {
            component: null,
            moduleType: null,
            moduleOpts: DynamicParams,
            isEdit: false,
            values: null,
            cached: [],
            cachedSides: [],
            files: [],
        }
    },
    computed: {
        contentClass: function () {
            if (this.hasSidebar) {
                return 'col-md-9'
            }
        },
    },
    watch: {
        '$route.params': function (params) {
            // console.log('params cambiato', params);
        },
        modules: function (modules) {
            this.init()
        }
    },
    methods: {
        init: function () {
            // imposta una variabile intermedia per poter modificare i moduli
            let sorted = orderBy(this.modules, ['order', 'created_at'], ['asc', 'asc'])
            if (sorted.length > 0) {
                for (let i = 0; i < sorted.length; i++) {
                    if (sorted[i].type === 'row') {
                        let sortedColumns = orderBy(sorted[i].content, ['order', 'created_at'], ['asc', 'asc'])
                        sortedColumns = sortedColumns.map(column => {
                            let sortedModules = orderBy(column.content.modules, ['order', 'created_at'], ['asc', 'asc'])
                            column.content.modules = sortedModules
                            return column
                        })
                        sorted[i].content = sortedColumns
                    }

                    let cache = {
                        ...sorted[i],
                        uuid: Uuid.get()
                    }

                    if (cache.type === 'row') {
                        cache.content = this.setInitials(cache.content)
                    }

                    this.cached.push(cache)
                }
            }
            // this.debug()
        },
        setInitials: function (objs) {
            for (let i = 0; i < objs.length; i++) {
                objs[i].uuid = Uuid.get()
                objs[i].isNew = false

                if (objs[i].content.hasOwnProperty('modules')) {
                    objs[i].content.modules = this.setInitials(objs[i].content.modules)
                }
            }
            return objs
        },

        debug: function () {
            if (this.cached.length === 0 && this.modelIdx !== 0) {
                this.$nextTick(() => {
                    this.newComponent('row')
                })
            }
        },
        addComponent: function () {
            this.$refs.componentSelector.show()
        },
        newComponent: function (type) {
            this.moduleType = type
            this.dismissModal()

            this.isEdit = false
            const newModule = {
                uuid: Uuid.get(),
                type: type,
                isNew: true,
                modulable_id: this.modelIdx,
                modulable_type: this.model,
                content: {},
            }

            // console.log(newModule);
            this.cached.push(newModule)
        },
        saveSubModule: function (subModule) {
            let idx = this.cached.findIndex(cache => cache.uuid === subModule.uuid)
            if (idx > -1) {
                this.cached.splice(idx, 1, subModule)
                this.savePage()
            }
            // console.log(idx, subModule.uuid);
        },
        deletedComponent: function (component) {
            let idx = this.cached.findIndex(cache => cache.uuid === component.uuid)
            if (idx > -1) {
                this.cached.splice(idx, 1)
            }
        },
        dismissModal: function () {
            this.$refs.componentSelector.hide()
        },
        setModule: function (module) {
            console.log('deprecata');
        },
        deleted: function (module) {
            this.moduleType = null
            this.$emit('deleted', module)
        },
        deleteComponent: function (id, isNew, uuid) {
            console.log('elimina componente', id, isNew, uuid);

            // se è nuovo elimina il componente dalla visuale
            if (isNew) {
                this.cached = this.searchAndDelete(this.cached, uuid)
            }
            else {
                // Se non è un nuovo modulo, effettua l'eliminazione sul DB
                let url = '/api/admin/delete-component/' + id
                this.$http.delete(url)
                    .then(response => {
                        // console.log(response.data);
                        // console.log('modulo eliminato', uuid);
                        if (response.data.success) {
                            this.cached = this.searchAndDelete(this.cached, uuid)
                        }
                    })
            }
        },
        searchAndDelete: function (objs, uuid) {
            let idx = objs.findIndex(obj => obj.uuid === uuid)
            if (idx > -1) {
                console.log('trovato');
                objs.splice(idx, 1)
            }
            else {
                for (let i = 0; i < objs.length; i++) {
                    if (objs[i].content.hasOwnProperty('modules')) {
                        objs[i].content.modules = this.searchAndDelete(objs[i].content.modules,
                            uuid)
                    }
                    else if (objs[i].content.length > 0) {
                        objs[i].content = this.searchAndDelete(objs[i].content, uuid)
                    }
                }
            }
            return objs
        },

        savePage: function () {
            console.log('salva pagina', this.cached);
            this.$root.$emit('close-all-panels')

            for (let i = 0; i < this.cached.length; i++) {
                // temps[i] = this.saveComponent(temps[i])
                switch (this.cached[i].type) {
                case 'row':
                    let rowData = Object.assign({}, this.cached[i])
                    delete rowData.content
                    rowData.content = {
                        columns: this.cached[i].content.length
                    }

                    rowData = this.formatRequest(rowData)
                    this.$http.post('/api/admin/save-component', rowData)
                        .then(rowResponse => {
                            this.cached[i] = this.formatFromResponse(this.cached[i], rowResponse.data.module)
                            let columns = this.cached[i].content
                            for (let j = 0; j < columns.length; j++) {
                                let modules = columns[j].content.modules
                                let columnData = Object.assign({}, columns[j])
                                columnData.modulable_id = rowResponse.data.module.id
                                columnData.modulable_type = 'App\\Module'
                                delete columnData.content.modules

                                columnData = this.formatRequest(columnData)
                                this.$http.post('/api/admin/save-component', columnData)
                                    .then(columnResponse => {
                                        this.cached[i].content[j] = this.formatFromResponse(this.cached[i].content[j], columnResponse.data.module)
                                        if (modules) {
                                            for (let k = 0; k < modules.length; k++) {
                                                let moduleData = Object.assign({}, modules[k])
                                                moduleData.modulable_id = columnResponse.data.module.id
                                                moduleData.modulable_type = 'App\\Module'
                                                moduleData = this.formatRequest(moduleData)
                                                this.$http.post('/api/admin/save-component', moduleData)
                                                    .then(moduleResponse => {
                                                        this.cached[i].content[j].modules[k] = this.formatFromResponse(modules[k], moduleResponse.data.module)
                                                    })
                                            }
                                        }
                                    })
                            }
                        })
                    break;
                default:
                    let data = this.formatRequest(this.cached[i])
                    this.$http.post('/api/admin/save-component', data)
                        .then(response => {
                            let temp = this.formatFromResponse(this.cached[i], response.data.module)
                            this.cached[i] = temp
                        })
                }
            }
        },
        formatFromResponse: function (obj, newObj) {
            let temp = Object.assign({}, obj, newObj)
            temp.id = Number(temp.id)
            temp.modulable_id = Number(temp.modulable_id)
            temp.order = Number(temp.order)
            temp.content = obj.content

            return temp
        },
        saveComponent: function (current) {

        },
        formatModuleData: function (source) {
            let obj = Object.assign({}, source)

        },
        formatRequest: function (obj) {
            let form = new FormData()
            // inserisco i campi normali
            for (let key in obj) {
                if (obj.hasOwnProperty(key)) {
                    if (key === 'content') {
                        let content = obj[key]

                        form.append(key, JSON.stringify(content))
                    }
                    else if (key === 'uuid') {
                        let hasFile = this.hasFile(obj[key])
                        // se nel contenuto del modulo c'è un file
                        if (hasFile) {
                            form.append('file', hasFile)
                        }
                    }
                    else {
                        form.append(key, obj[key])
                    }
                }
            }
            return form
        },
        hasFile: function (uuid) {
            // console.log(uuid);
            let idx = this.files.findIndex(file => file.uuid === uuid)
            if (idx > -1) {
                return this.files[idx].file
            }
            return false
        },
        sortModules: function (modules) {
            this.cached = this.cached.map((cache, i) => {
                let newModule = Object.assign({}, cache)
                newModule['order'] = i
                return newModule
            })

            this.savePage()
        }
    },
    mounted: function () {
        this.init()
        this.$root.$on('add-file-to-upload', obj => {
            let idx = this.files.findIndex(file => file.uuid === obj.uuid)
            if (idx > -1) {
                this.files[idx].file = obj.file
            }
            else {
                this.files.push(obj)
            }
        })
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
