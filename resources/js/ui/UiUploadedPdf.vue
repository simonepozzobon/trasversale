<template>
<div class="ui-pdf">
    <div
        class="ui-pdf__icon"
        @click="openPdf"
    >
        <pdf-file
            width="64"
            height="64"
            :has-hex-color="true"
            :color="this.color"
        />
    </div>
    <div
        @click="openPdf"
        class="ui-pdf__name"
        ref="name"
    >
        {{ name }}
    </div>
</div>
</template>

<script>
import {
    PdfFile,
}
from '../icons'

export default {
    name: 'UiUploadedPdf',
    components: {
        PdfFile,
    },
    props: {
        src: {
            type: String,
            default: null,
        },
        name: {
            type: String,
            default: 'filename.pdf',
        },
        color: {
            type: String,
            default: null,
        },
    },
    watch: {
        color: function () {
            this.setColor()
        },
    },
    methods: {
        setColor: function () {
            this.$refs.name.style.color = this.color
        },
        openPdf: function () {
            if (this.src) {
                window.open(this.src, '_blank')
            }
        }
    },
    mounted: function () {
        this.setColor()
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.ui-pdf {
    cursor: pointer;
    display: flex;
    align-items: center;

    &:hover {
        text-decoration: underline;
    }
}
</style>
