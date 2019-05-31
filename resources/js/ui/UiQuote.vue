<template>
<div class="ui-quote">
    <blockquote
        :cite="source"
        class="blockquote"
    >
        <p class="mb-0">
            {{ quote }}
        </p>

        <footer
            class="blockquote-footer"
            v-if="source"
        >
            {{source}}
        </footer>
    </blockquote>
</div>
</template>

<script>
export default {
    name: 'UiQuote',
    props: {
        quote: {
            type: String,
            default: null,
        },
        source: {
            type: String,
            default: null,
        },
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.ui-quote {
    padding-top: $spacer * $line-height-base;
}
</style>
