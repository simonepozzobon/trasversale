<template>
<div class="team-table">
    <b-table
        striped
        hover
        :items="members"
        :fields="fields"
    >
        <template
            slot="img"
            slot-scope="data"
        >
            <img
                :src="data.item.img"
                class="team-table__avatar"
            />
        </template>
        <template
            slot="social"
            slot-scope="data"
        >
            <div
                class="team-table__social"
                v-for="(social, i) in data.item.social"
                :key="i"
            >
                <a
                    :href="social[Object.keys(social)[0]]"
                    target="_blank"
                >
                    {{ Object.keys(social)[0] }}
                </a>
            </div>
        </template>
        <template
            slot="tools"
            slot-scope="data"
        >
            <button
                class="btn btn-outline-primary"
                @click="editMember(data.item)"
            >
                Modifica
            </button>
            <button
                class="btn btn-outline-danger"
                @click="deleteMember(data.item)"
            >
                Elimina
            </button>
        </template>
    </b-table>
</div>
</template>

<script>
export default {
    name: 'TeamTable',
    props: {
        members: {
            type: Array,
            default: function () {
                return []
            },
        },
    },
    data: function () {
        return {
            fields: [{
                    key: 'img',
                    label: 'Image',
                    sortable: false,
                },
                {
                    key: 'name',
                    label: 'Nome',
                    sortable: true
                },
                {
                    key: 'role',
                    label: 'Ruolo',
                    sortable: true,
                },

                {
                    key: 'social',
                    label: 'Social',
                    sortable: true,
                },
                {
                    key: 'tools',
                    label: 'Tools',
                    sortable: false
                }
            ]
        }
    },
    methods: {
        editMember: function (item) {
            console.log(item, this.members);
            this.$emit('edit-member', item)
        },
        deleteMember: function (item) {
            let idx = this.members.findIndex(member => member.id === item.id)
            if (idx > -1) {
                let newMembers = Object.assign([], this.members)
                newMembers.splice(idx, 1)
                this.$emit('update:members', newMembers)
            }
        }
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.team-table {
    &__avatar {
        max-width: 100%;
        width: 120px;
        height: auto;
    }
}
</style>
