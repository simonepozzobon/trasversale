<template>
<div class="team-module">
    <div class="form-group row">
        <div class="col-12">
            <h5>Impostazioni generali modulo</h5>
            <hr />
        </div>
    </div>
    <counter-module
        label="Numero di colonne"
        name="colnum"
        :max="4"
        :min="2"
        :initial="colSize"
        @changed="updateNumCol"
    />

    <div class="form-group row pt-4">
        <div class="col-12">
            <h5>Aggiungi Componente</h5>
            <hr />
        </div>
    </div>
    <team-form
        :obj.sync="form"
        @update-member="addMember"
        :is-edit="isEdit"
        @undo="undoEdit"
    />

    <div class="form-group row pt-4">
        <div class="col-12">
            <h5>Modifica Team</h5>
            <hr />
        </div>
    </div>
    <team-table
        :members.sync="obj.people"
        @edit-member="editMember"
    />
</div>
</template>

<script>
import CounterModule from '../counter/CounterModule.vue'
import TeamForm from './TeamForm.vue'
import TeamTable from './TeamTable.vue'
import {
    Uuid
}
from '../../../Utilities'

export default {
    name: 'TeamModule',
    components: {
        CounterModule,
        TeamForm,
        TeamTable,
    },
    props: {
        initial: {
            type: Object,
            default: function () {
                return {}
            },
        },
    },
    data: function () {
        return {
            obj: {
                people: [],
                gridCol: 0,
            },
            colSize: 0,
            isOpen: false,
            isEdit: false,
            form: {
                name: null,
                role: null,
                description: null,
                img: null,
                social: [],
                file: null,
            }
        }
    },
    watch: {
        obj: {
            handler: function (obj) {
                this.$emit('update', obj)
            },
            deep: true,
        }
    },
    methods: {
        debug: function () {},
        updateNumCol: function (cols) {
            let colSize = 12 / Number(cols)
            this.obj['gridCol'] = colSize
            this.colSize = Number(cols)

        },
        addMember: function (member) {
            if (this.isEdit) {
                let idx = this.obj['people'].findIndex(person => person.id === member.id)
                if (idx > -1) {
                    this.obj['people'].splice(idx, 1, member)
                    this.isEdit = false
                }
            }
            else {
                this.obj['people'].push(member)
            }
        },
        editMember: function (member) {
            this.isEdit = true
            this.form = member
        },
        undoEdit: function () {
            this.isEdit = false
        },
    },
    created: function () {
        if (this.initial) {
            this.colSize = 12 / Number(this.initial['gridCol'])
            this.obj['gridCol'] = this.initial['gridCol']
            this.obj['people'] = this.initial['people']
        }
    },
    mounted: function () {
        // console.log(this.obj);
        // this.debug()
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
