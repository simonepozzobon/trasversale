<template>
<b-modal
    ref="modal"
    title="Seleziona il componente"
>
    <div class="form-group">
        <label for="component">Seleziona Componente</label>
        <select
            name="component"
            class="form-control"
            v-model="moduleType"
        >
            <option
                v-for="(module, i) in modules"
                :key="i"
                :value="module.value"
            >
                {{module.label}}
            </option>
        </select>
    </div>

    <template slot="modal-footer">
        <button
            class="btn btn-outline-secondary"
            @click="dismissModal"
        >
            Chiudi
        </button>
        <button
            class="btn btn-outline-primary"
            @click="newComponent"
        >
            Aggiungi
        </button>
    </template>
</b-modal>
</template>

<script>
export default {
    name: 'ComponentsList',
    props: {
        exclude: {
            type: Array,
            default: function () {
                return []
            },
        },
    },
    data: function () {
        return {
            moduleType: null,
            modules: [{
                    label: 'Nessun Componente',
                    value: null,
                },
                {
                    value: 'title',
                    label: 'Titolo',
                },
                {
                    value: 'paragraph',
                    label: 'Paragrafo',
                },
                {
                    value: 'image',
                    label: 'Immagine',
                },
                {
                    value: 'video',
                    label: 'Video',
                },
                {
                    value: 'quote',
                    label: 'Citazione',
                },
                {
                    value: 'grid',
                    label: 'Griglia',
                },
                {
                    value: 'row',
                    label: 'Tabella',
                },
                {
                    value: 'calendar',
                    label: 'Calendario',
                },
                {
                    value: 'team',
                    label: 'Team',
                },
                {
                    value: 'map',
                    label: 'Mappa',
                },
                {
                    value: 'contact-form',
                    label: 'Modulo Contatti',
                },
                {
                    value: 'uploadpdf',
                    label: 'File Pdf',
                },
                {
                    value: 'spacer',
                    label: 'Spaziatore',
                },
            ]
        }
    },
    methods: {
        show: function () {
            this.$refs.modal.show()
        },
        hide: function () {
            this.$refs.modal.hide()
        },
        newComponent: function () {
            this.$emit('new-component', this.moduleType)
        },
        dismissModal: function () {
            this.$refs.modal.hide()
        },
        removeOpt: function (opt) {
            let idx = this.modules.findIndex(module => module.value === opt)
            this.modules.splice(idx, 1)
        }
    },
    created: function () {
        if (this.exclude.length > 0) {
            for (let i = 0; i < this.exclude.length; i++) {
                this.removeOpt(this.exclude[i])
            }
        }
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
