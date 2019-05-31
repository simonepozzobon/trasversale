<template lang="html">
    <b-modal ref="modal" title="Seleziona il componente">
        <div class="form-group">
            <label for="component">Seleziona Componente</label>
            <select name="component" class="form-control" v-model="moduleType">
                <option :value="null">Nessun Componente</option>
                <option value="title">Titolo</option>
                <option value="paragraph">Paragrafo</option>
                <option value="image">Immagine</option>
                <option value="video">Video</option>
                <option value="quote">Citazione</option>
                <option value="grid">Griglia</option>
                <option value="row">Griglia Contenuti</option>
                <option value="calendar">Calendario</option>
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
</template>

<script>
export default {
    name: 'ComponentsList',
    data: function() {
        return {
            moduleType: null,
        }
    },
    methods: {
        show: function() {
            this.$refs.modal.show()
        },
        hide: function() {
            this.$refs.modal.hide()
        },
        newComponent: function() {
            this.$emit('new-component', this.moduleType)
        },
        dismissModal: function() {
            this.$refs.modal.hide()
        },
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
