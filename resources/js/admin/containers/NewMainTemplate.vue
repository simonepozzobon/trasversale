<template>
<div
    ref="container"
    class="new-main-template col-9"
    :class="activeClass"
>
    <slot></slot>
    <div class="new-main-template__header">
        <div class="new-main-template__head">
            <div
                class="new-main-template__title"
                v-if="hasTitle && active"
            >
                <h1 class="pt-3">{{ title }}</h1>
            </div>
            <div
                v-else-if="!active"
                class="new-main-template__title"
            >
                <button
                    class="btn btn-outline-dark"
                    @click="editMain"
                >
                    Modifica Contenuto Principale
                </button>
            </div>
            <div
                class="new-main-template__action"
                v-if="active"
            >
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
                    {{ customSave }}
                </button>
                <button
                    class="btn btn-outline-danger ml-auto"
                    @click="deleteAll"
                >
                    Elimina tutto
                </button>
            </div>
        </div>
    </div>
    <div
        ref="content"
        class="new-main-template__content"
    >
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
    <div class="new-main-template__footer">
        <button
            v-if="active"
            class="btn btn-outline-primary"
            @click="addComponent"
        >
            Aggiungi Componente
        </button>
        <button
            v-if="active"
            class="btn btn-outline-success ml-2"
            @click="savePage"
        >
            {{ customSave }}
        </button>
        <button
            class="btn btn-outline-danger ml-auto"
            v-if="active"
            @click="deleteAll"
        >
            Elimina tutto
        </button>
    </div>
    <components-list
        ref="componentSelector"
        @new-component="newComponent"
    />
</div>
</template>

<script>
import ComponentsList from '../components/ComponentsList.vue'
import draggable from 'vuedraggable'
import DynamicParams from '../DynamicParams'
import EditPanel from '../components/EditPanel.vue'
import ModuleContainer from './ModuleContainer.vue'

import {
    Uuid,
    SizeUtil,
}
from '../../Utilities'

const orderBy = require('lodash.orderby')

export default {
    name: 'NewMainTemplate',
    components: {
        ComponentsList,
        draggable,
        EditPanel,
        ModuleContainer,
    },
    props: {
        isPost: {
            type: Boolean,
            default: false,
        },
        active: {
            type: Boolean,
            default: false,
        },
        contentHeight: {
            type: Number,
            default: 0,
        },
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
        hasTitle: {
            type: Boolean,
            default: true,
        },
        customSave: {
            type: String,
            default: 'Salva Pagina'
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
            loaded: 0,
            counter: 0,
            hasAwait: false,
        }
    },
    watch: {
        modules: function (modules) {
            this.init()
        },
        model: function (model) {
            this.setModelKey('modulable_type', model)
        },
        modelIdx: function (id) {
            this.setModelKey('modulable_id', id)
        },
        contentHeight: function (height) {
            this.$refs.content.style.minHeight = height + 'px'
        }
    },
    computed: {
        activeClass: function () {
            if (this.active) {
                return 'new-main-template--is-active'
            }
            return null
        }
    },
    methods: {
        editMain: function () {
            this.$emit('edit-main')
        },
        setModelKey: function (key, value) {
            for (let i = 0; i < this.cached.length; i++) {
                this.cached[i][key] = value
            }
        },
        init: function () {
            // console.log('iniiinini');
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
        debug: function () {
            // if (this.cached.length === 0 && this.modelIdx !== 0) {
            //     this.$nextTick(() => {
            //         this.newComponent('contact-form')
            //     })
            // }
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
        addComponent: function () {
            this.$refs.componentSelector.show()
        },
        dismissModal: function () {
            this.$refs.componentSelector.hide()
        },
        deletedComponent: function (component) {
            let idx = this.cached.findIndex(cache => cache.uuid === component.uuid)
            if (idx > -1) {
                this.cached.splice(idx, 1)
            }
        },
        deleteAll: function () {
            // this.$emit('delete-all')
            let cached = Object.assign([], this.cached)
            for (var i = 0; i < cached.length; i++) {
                let data = Object.assign({}, cached[i])
                // console.log(data);
                this.deleteComponent(data.id, data.isNew, data.uuid)
            }
        },
        saveComponent: function (current) {

        },
        deleteComponent: function (id = false, isNew, uuid) {
            // console.log('elimina componente', id, isNew, uuid);

            // se è nuovo elimina il componente dalla visuale
            if (isNew || id == false) {
                this.cached = this.searchAndDelete(this.cached, uuid)
            }
            else {
                // Se non è un nuovo modulo, effettua l'eliminazione sul DB
                let url = '/api/admin/delete-component/' + id
                this.$http.delete(url)
                    .then(response => {
                        console.log(response.data);
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
                // console.log('trovato');
                objs.splice(idx, 1)
            }
            else {
                for (let i = 0; i < objs.length; i++) {
                    if (objs[i].content.hasOwnProperty('modules')) {
                        objs[i].content.modules = this.searchAndDelete(objs[i].content.modules, uuid)
                    }
                    else if (objs[i].content.length > 0) {
                        objs[i].content = this.searchAndDelete(objs[i].content, uuid)
                    }
                }
            }
            return objs
        },
        saveSubModule: function (subModule) {
            let idx = this.cached.findIndex(cache => cache.uuid === subModule.uuid)
            if (idx > -1) {
                this.cached.splice(idx, 1, subModule)
                this.savePage()
            }
            // console.log(idx, subModule.uuid);
        },
        savePage: function (event = null, modelSaved = false) {
            // if (modelSaved) {
            //     this.$emit('save-page')
            // }

            if (this.isPost && modelSaved == false) {
                // console.log('before save main')
                this.$emit('before-save', 'main')
                return null
            }

            // console.log('this.model', this.model);
            if (this.model) {
                this.$root.$emit('close-all-panels')
                this.counter = this.cached.length
                // console.log('cached', this.cached);
                let promises = []
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

                        let requestRow = this.$http.post('/api/admin/save-component', rowData)
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
                                    let requestColumn = this.$http.post('/api/admin/save-component', columnData)
                                        .then(columnResponse => {
                                            this.cached[i].content[j] = this.formatFromResponse(this.cached[i].content[j], columnResponse.data.module)
                                            if (modules) {
                                                for (let k = 0; k < modules.length; k++) {
                                                    let moduleData = Object.assign({}, modules[k])
                                                    moduleData.modulable_id = columnResponse.data.module.id
                                                    moduleData.modulable_type = 'App\\Module'
                                                    moduleData = this.formatRequest(moduleData)
                                                    let requestModule = this.$http.post('/api/admin/save-component', moduleData)
                                                        .then(moduleResponse => {
                                                            let newModule = this.formatFromResponse(modules[k], moduleResponse.data.module)
                                                            this.cached[i].content[j].modules[k] = newModule
                                                            if (!this.cached[i].content[j].content.hasOwnProperty('modules')) {
                                                                this.cached[i].content[j].content.modules = []
                                                                this.cached[i].content[j].content.modules[k] = newModule
                                                            }
                                                            else {
                                                                this.cached[i].content[j].content.modules[k] = newModule
                                                            }
                                                        })
                                                    promises.push(requestModule)
                                                }
                                            }
                                        })
                                    promises.push(requestColumn)
                                }
                            })
                        promises.push(requestRow)
                        break;
                    case 'team':
                        // console.log(i);
                        // wait uploads before run promises
                        this.hasAwait = true

                        let teamObj = this.cached[i]
                        let content = teamObj.content.team
                        let people = this.saveImage(content.people).then(people => {
                            // console.log(teamObj);
                            let teamData = this.formatRequest(teamObj)
                            let teamRequest = this.$http.post('/api/admin/save-component', teamData)
                                .then(response => {
                                    let temp = this.formatFromResponse(this.cached[i], response.data.module)
                                    this.cached[i] = temp
                                })
                            promises.push(teamRequest)
                            if (this.hasAwait) {
                                this.processAllPromises(promises)
                                this.hasAwait = false
                                // console.log('dentro', promises);
                            }
                        })


                        break;

                    default:
                        // console.log('default');
                        let data = this.formatRequest(this.cached[i])
                        let request = this.$http.post('/api/admin/save-component', data)
                            .then(response => {
                                let temp = this.formatFromResponse(this.cached[i], response.data.module)
                                this.cached[i] = temp
                            })
                        promises.push(request)
                    }

                    // if (i === this.cached.length - 1 && this.hasAwait) {
                    // console.log('fine', i, this.cached.length, this.hasAwait);
                    // }
                }


                if (!this.hasAwait) {
                    this.processAllPromises(promises)
                    // console.log('fuori');
                }
            }
        },
        formatRequest: function (obj) {
            // console.log(obj);
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
        processAllPromises: async function (promises) {
            return await this.$http.all(promises)
                .then(results => {
                    // console.log('completato', results);
                    // if (modelSaved) {
                    this.$emit('notify', {
                        uuid: Uuid.get(),
                        title: 'Pagina Salvata',
                        message: 'Salvataggio Completato'
                    })
                    // }
                })
                .catch(err => {
                    // console.error(err);
                    this.$emit('notify', {
                        uuid: Uuid.get(),
                        title: 'Errore',
                        // message: 'Errore nel salvataggio, guarda la console per maggiori dettagli'
                    })
                })
        },
        saveImage: async function (people) {
            // console.log('start loop');
            for (let i = 0; i < people.length; i++) {
                if (people[i].hasOwnProperty('file')) {
                    let fileData = new FormData()
                    fileData.append('file', people[i].file)

                    people[i] = await this.$http.post('/api/admin/utilities/save-image', fileData).then(response => {
                        people[i].img = response.data.file.src
                        delete people[i].file
                        // console.log('upload finito');
                        return people[i]
                    })
                }
            }
            return people
        },
        formatFromResponse: function (obj, newObj) {
            let temp = Object.assign({}, obj, newObj)
            temp.id = Number(temp.id)
            temp.modulable_id = Number(temp.modulable_id)
            temp.isNew = false
            temp.order = Number(temp.order)
            temp.content = obj.content
            // console.log('temp', temp, obj, newObj);
            return temp
        },
        sortModules: function (modules) {
            this.cached = this.cached.map((cache, i) => {
                let newModule = Object.assign({}, cache)
                newModule['order'] = i
                return newModule
            })

            this.savePage()
        },
        newComponent: function (type) {
            this.moduleType = type
            this.dismissModal()

            this.isEdit = false
            const newModule = {
                uuid: Uuid.get(),
                type: type,
                isNew: true,
                order: this.cached.length,
                modulable_id: this.modelIdx,
                modulable_type: this.model,
                content: {},
            }

            // console.log(newModule);
            this.cached.push(newModule)
        },
    },
    mounted: function () {
        // console.log('main');
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
@import '~styles/shared';

.new-main-template {
    padding: $spacer * 2;
    min-height: 100vh;
    max-width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;

    // background: linear-gradient(45deg, rgba($green, $opacity-test), rgba($teal, $opacity-test), rgba($cyan, $opacity-test), rgba($blue, $opacity-test), rgba($indigo, $opacity-test), rgba($purple, $opacity-test), rgba($pink, $opacity-test), rgba($red, $opacity-test), rgba($orange, $opacity-test), rgba($yellow, $opacity-test),);
    // background-size: 800% 800%;
    // animation: Gradient 360s ease infinite;

    &__content {
        margin-top: $spacer * 4;
        margin-bottom: $spacer * 4;
        padding: $spacer * 2;
        width: 100%;
        // @include gradient-directional(rgba($gray-300, 0), rgba($light, 0), 135deg);
        @include border-radius($spacer / 2);
        // @include box-shadows($gray-500);
    }

    &__header {
        width: 100%;
        margin-top: $spacer * 4;
        padding: $spacer * 2;
        // @include gradient-directional(rgba($gray-300, 0), rgba($light, 0), 135deg);
        @include border-radius($spacer / 2);
        // @include box-shadows($gray-500);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    &__head {
        width: 100%;
        flex-wrap: wrap;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    &__action {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    &__footer {
        width: 100%;
        margin-top: $spacer * 4;
        margin-bottom: $spacer * 4;
        padding: $spacer * 2;
        // @include gradient-directional(rgba($gray-300, 0), rgba($light, 0), 135deg);
        @include border-radius($spacer / 2);
        // @include box-shadows($gray-500);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    &--no-title &__head {
        // justify-content: space-between;
        // justify-content: center;
    }

    &--is-active & {
        &__content {
            @include gradient-directional($gray-300, $light, 135deg);
            @include box-shadows($gray-500);
            transition: $transition-base;
        }

        &__header {
            @include gradient-directional($gray-300, $light, 135deg);
            @include box-shadows($gray-500);
            transition: $transition-base;
        }

        &__footer {
            @include gradient-directional($gray-300, $light, 135deg);
            @include box-shadows($gray-500);
            transition: $transition-base;
        }
    }
}
</style>
