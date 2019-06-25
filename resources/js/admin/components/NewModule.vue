<template>
<div
    class="new-module"
    ref="container"
>
    <div class="new-module__container">
        <dynamic-module
            v-if="module"
            :name="name"
            :uuid="uuid"
            :options="module.options"
            :values="values"
            :is-edit="isEdit"
            @changed="setObj"
        />
        <hr>
        <div class="new-module__tools">
            <button
                class="btn btn-outline-secondary"
                @click="closeComponent"
            >
                Chiudi
            </button>
            <button
                class="btn btn-outline-danger"
                @click="deleteComponent"
            >
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
        uuid: {
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
            // console.log('show values', this.values);
            if (this.master) {
                this.master.play()
            }
        },
        hide: function () {
            if (this.master) {
                this.master.reverse()
            }
        },
        setObj: function (obj) {
            this.obj = obj
            // console.log(obj);
            this.$emit('changed', obj)
        },
        saveComponent: function () {
            console.log('deprecata');
            this.hide()
            this.$emit('save')
        },
        closeComponent: function () {
            this.$emit('close')
        },
        deleteComponent: function () {
            this.$emit('delete', this.moduleId, this.isNew)
        }
    },
    mounted: function () {
        // console.log('module.options', this.module);
        this.$nextTick(this.init)
    },
    beforeDestroy: function () {
        if (this.master) {
            this.master.kill()
        }
    }
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
