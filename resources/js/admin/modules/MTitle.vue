<template lang="html">
    <div class="form">
        <div class="form-group row">
            <label for="title" class="col-md-3">Titolo</label>
            <div class="col-md-9">
                <input type="text" name="title" v-model="title" placeholder="Titolo ..." class="form-control">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'MTitle',
    data: function() {
        return {
            title: null
        }
    },
    watch: {
        'title': function(title) {
            this.$emit('changed', {
                title: title
            })
        }
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
