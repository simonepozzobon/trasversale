<template>
<div class="new-module"
    ref="container">
    <div class="new-module__container">
        <dynamic-module v-if="module"
            :name="name"
            :options="module.options"
            :values="values"
            :is-edit="isEdit"
            @changed="setObj" />
        <hr>
        <div class="new-module__tools">
            <button class="btn btn-outline-primary"
                @click="saveComponent">
                Salva Modifiche
            </button>
            <button class="btn btn-outline-secondary"
                @click="closeComponent">
                Chiudi
            </button>
            <button class="btn btn-outline-danger"
                @click="deleteComponent">
                Elimina Componente
            </button>
        </div>
    </div>
</div>
</template>

<script>
import {
    SizeUtil,
}
from '../../Utilities'
import BlockColumnsPreview from './rowcolumn/BlockColumnsPreview.vue'
import DynamicModule from './DynamicModule.vue'
import DynamicParams from '../DynamicParams'

export default {
    name: 'NewModule',
    components: {
        BlockColumnsPreview,
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
        isEdit: {
            type: Boolean,
            default: false,
        },
        isNew: {
            type: Boolean,
            default: true,
        },
        values: [Object, Array],
    },
    data: function () {
        return {
            obj: null,
        }
    },
    computed: {
        module: function () {
            return DynamicParams.filter(params => params.name == this.name)[0]
        },
    },
    methods: {
        init: function () {
            let el = this.$refs.container
            let size = SizeUtil.get(el)
            // console.log(size);
            this.master = new TimelineMax({
                paused: true,
                yoyo: true
            })

            this.master.fromTo(el, .5, {
                className: '-=new-module--open',
                autoAlpha: 0,
                ease: Power4.easeInOut,
            }, {
                className: '+=new-module--open',
                autoAlpha: 1,
                ease: Back.easeOut.config(1.2),
            }, 0)

            this.master.progress(1)
                .progress(0)

        },
        show: function () {
            this.master.play()
        },
        hide: function () {
            this.master.reverse()
        },
        setObj: function (obj) {
            this.obj = obj
            this.$emit('changed', obj)
        },
        saveComponent: function () {

            this.hide()
            this.$emit('save')

            // let request = {
            //     id: this.moduleId,
            //     model_id: this.modelIdx,
            //     model: this.model,
            //     module: this.name,
            //     data: this.obj,
            // }
            // this.$emit('save', this.obj)
            // console.log('formatto richiesta', request);
            // console.log('save component newmodule', request);

            // let data = this.formatRequest(request)

            // this.$http.post('/api/admin/save-component', data)
            //     .then(response => {
            //         console.log(response.data);
            //         if (response.data.success) {
            //             this.$emit('saved', response.data.module)
            //             this.closeComponent()
            //         }
            //     })
        },
        formatRequest: function (obj) {
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
                    }
                    else {
                        form.append(key, obj[key])
                    }
                }
            }

            return form
        },
        hasFile: function (obj) {
            // https://stackoverflow.com/questions/31525667/check-if-variable-holds-file-or-blob
            for (let key in obj) {
                if (obj.hasOwnProperty(key) && obj[key] instanceof File) {
                    return key
                }
            }
            return false
        },
        closeComponent: function () {
            this.$emit('close')
        },
        deleteComponent: function () {
            // Se non è un nuovo modulo, effettua l'eliminazione sul DB
            if (this.moduleId && !this.isNew) {
                let url = '/api/admin/delete-component/' + this.moduleId
                this.$http.delete(url)
                    .then(response => {
                        // console.log(response.data);
                        if (response.data.success) {
                            this.$emit('deleted', response.data.module)
                        }
                    })
            }
            // se è un nuovo modulo emette l'evento deleted
            else {
                this.$emit('deleted', false)
            }

        }
    },
    mounted: function () {
        this.$nextTick(this.init)
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.new-module {
    position: relative;
    height: 0;
    overflow: hidden;

    &--open {
        height: auto;
    }

    &__container {
        padding: $spacer;
        margin-top: $spacer * 2;
        background-color: rgba($white, .3);
        @include border-radius(10px);
    }

    &__tools {
        display: flex;
        justify-content: space-between;

        button {}
    }
}
</style>
