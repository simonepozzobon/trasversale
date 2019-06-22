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
    <team-form @update-member="addMember" />

    <div class="form-group row pt-4">
        <div class="col-12">
            <h5>Modifica Team</h5>
            <hr />
        </div>
    </div>
    <team-table :members.sync="obj.people" />
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
        }
    },
    watch: {
        obj: {
            handler: function (obj) {
                this.$emit('update', obj)
                console.log(obj);

            },
            deep: true,
        }
    },
    methods: {
        debug: function () {
            let debug = [{
                    "id": 1,
                    "img": "/dummies/main-grid/dummy_2.jpg",
                    "name": "Valeria Turchi",
                    "role": "co-founder",
                    "social": [{
                            "twitter": "https://twitter.com"
                        },
                        {
                            "linkedin": "https://linkedin.com"
                        }
                    ],
                    "description": "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
                },
                {
                    "id": 2,
                    "img": "/dummies/main-grid/dummy_5.jpg",
                    "name": "Federica Iurcovich",
                    "role": "co-founder",
                    "social": [{
                            "twitter": "https://twitter.com"
                        },
                        {
                            "linkedin": "https://linkedin.com"
                        }
                    ],
                    "description": "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
                },
            ]

            for (let i = 0; i < debug.length; i++) {
                debug[i].id = Uuid.get()
            }

            this.obj.people = debug
        },
        updateNumCol: function (cols) {
            let colSize = 12 / cols
            this.obj['gridCol'] = colSize
            this.colSize = cols

        },
        addMember: function (member) {
            this.obj['people'].push(member)
        }
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
