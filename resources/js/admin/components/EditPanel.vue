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
                :name="name"/>
        </div>
        <hr>
        <div class="edit-panel__footer">
            <button
                class="btn btn-outline-primary" @click="saveComponent">
                Salva Modifiche
            </button>
            <button
                class="btn btn-outline-secondary">
                Annulla
            </button>
            <button
                class="btn btn-outline-danger">
                Elimina Componente
            </button>
        </div>
    </div>
</template>

<script>
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
        }
    },
    data: function() {
        return {
            obj: null,
        }
    },
    methods: {
        setObj: function(obj) {
            this.obj = obj
        },
        saveComponent: function() {
            this.$http.post('/api/admin/save-component', this.obj).then(response => {
                console.log(response);
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
