<template lang="html">
    <ui-packery-container
        :items="this.items" />
</template>

<script>
import { UiPackeryContainer } from '../ui'
export default {
    name: 'MainGridContainer',
    components: {
        UiPackeryContainer,
    },
    data: function() {
        return {
            items: [
                {
                    id: 1,
                    bgColor: 'blue',
                    width: 4,
                    height: 4,
                    content: 'Ciao'
                },
                {
                    id: 2,
                    bgColor: 'red',
                    width: 5,
                    height: 2,
                    content: 'Ciao 2'
                },
                {
                    id: 3,
                    bgColor: 'yellow',
                    width: 3,
                    height: 2,
                    content: 'Ciao 3'
                },
                {
                    id: 4,
                    bgColor: 'green',
                    width: 6,
                    height: 2,
                    content: 'Ciao 4'
                },
                {
                    id: 5,
                    bgColor: 'pink',
                    width: 2,
                    height: 2,
                    content: 'Ciao 5'
                },
            ]
        }
    }
}
</script>

<style lang="css">
</style>
