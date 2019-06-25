<template>
<div class="map-table">
    <b-table
        striped
        hover
        :items="addresses"
        :fields="fields"
    >
        <template
            slot="tools"
            slot-scope="data"
        >
            <button
                class="btn btn-outline-danger"
                @click="deleteAddress(data.item)"
            >
                Elimina
            </button>
        </template>
    </b-table>
</div>
</template>

<script>
export default {
    name: 'MapTable',
    props: {
        addresses: {
            type: Array,
            default: function () {
                return []
            },
        },
    },
    data: function () {
        return {
            fields: [{
                    key: 'title',
                    label: 'Titolo',
                    sortable: true
                },
                {
                    key: 'lng',
                    label: 'Longitudine',
                    sortable: true,
                },
                {
                    key: 'lat',
                    label: 'Latitudine',
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
        deleteAddress: function (item) {
            let idx = this.addresses.findIndex(address => address.uuid === item.uuid)
            if (idx > -1) {
                let newAddresses = Object.assign([], this.addresses)
                newAddresses.splice(idx, 1)
                this.$emit('update:adresses', newAddresses)
            }
        }
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
