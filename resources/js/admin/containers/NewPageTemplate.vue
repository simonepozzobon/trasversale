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
                            <button class="btn btn-outline-primary"
                                @click="addComponent">
                                Aggiungi Componente
                            </button>
                            <button class="btn btn-outline-success"
                                @click="savePage">
                                Salva Pagina
                            </button>
                        </div>
                    </div>
                </div>
                <div class="page-template__content">
                    <module-container v-for="(module, i) in cached"
                        :key="module.uuid"
                        :item="module"
                        :model="model"
                        :model-idx="modelIdx"
                        @deleted="deletedComponent"
                        @save="saveComponent"
                        @delete="deleteComponent" />
                </div>
                <div class="page-template__footer">
                    <button class="btn btn-outline-primary"
                        @click="addComponent">
                        Aggiungi Componente
                    </button>
                    <button class="btn btn-outline-success ml-2"
                        @click="savePage">
                        Salva Pagina
                    </button>
                </div>
                <components-list ref="componentSelector"
                    @new-component="newComponent" />
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
            if (this.modules.length > 0) {
                for (let i = 0; i < this.modules.length; i++) {
                    let cache = {
                        ...this.modules[i],
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
                        console.log('modulo eliminato', uuid);
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
                        objs[i].content.modules = this.searchAndDelete(objs[i].content.modules, uuid)
                    }
                    else if (objs[i].content.length > 0) {
                        objs[i].content = this.searchAndDelete(objs[i].content, uuid)
                    }
                }
            }
            return objs
        },
        saveComponent: function (component) {
            let idx = this.cached.findIndex(cache => cache.uuid === component.uuid)
            if (idx > -1) {
                // if (this.cached[idx].isNew === true) {
                //     this.cached[idx].isNew = false
                // }

                console.log('save component', component);
                let data = {
                    ...component,
                    content: JSON.stringify(component.content)
                }

                this.$http.post('/api/admin/save-component', data)
                    .then(response => {

                        let module = {
                            ...response.data.module,
                            uuid: component.uuid,
                            isNew: false,
                            content: JSON.parse(response.data.module.content)
                        }

                        this.cached[idx] = module

                        console.log(response.data);
                    })
            }
        },
        savePage: function () {
            console.log('salva pagina');
            for (let i = 0; i < this.cached.length; i++) {
                let current = this.cached[i]
                console.log(current);
                this.saveComponent(current)
            }
        }
    },
    mounted: function () {
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
