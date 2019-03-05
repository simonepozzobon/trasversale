<template lang="html">
    <div>
        <h1>{{ title }}</h1>
    </div>
</template>

<script>
export default {
    name: 'UiTitle',
    props: {
        title: {
            type: String,
            default: 'Titolo'
        }
    }
}
</script>

<style lang="css" scoped>
</style>
