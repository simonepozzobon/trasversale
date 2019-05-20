<template lang="html">
    <div class="editor">
        <editor-menu-bar
            :editor="editor"
            v-slot="{ commands, isActive }">

            <div class="menubar">
                <button
                    class="menubar__button"
                    :class="{ 'is-active': isActive.bold() }"
                    @click="commands.bold">
                    B
                </button>

                <button
                    class="menubar__button"
                    :class="{ 'is-active': isActive.italic() }"
                    @click="commands.italic">
                    I
                </button>

                <button
                    class="menubar__button"
                    :class="{ 'is-active': isActive.strike() }"
                    @click="commands.strike">
                    S
                </button>

                <button
                    class="menubar__button"
                    :class="{ 'is-active': isActive.underline() }"
                    @click="commands.underline">
                    U
                </button>

                <button
                    class="menubar__button"
                    :class="{ 'is-active': isActive.bullet_list() }"
                    @click="commands.bullet_list">
                    List
                </button>

            </div>
        </editor-menu-bar>
        <editor-content
            class="editor__content"
            :editor="editor">
        </editor-content>
    </div>
</template>

<script>
import EnterHandler from './editor/EnterHandler'
import PasteHandler from './editor/PasteHandler'

import {
    Editor,
    EditorContent,
    EditorMenuBar,
} from 'tiptap'


import {
    HardBreak,
    OrderedList,
    BulletList,
    ListItem,
    Bold,
    Italic,
    Link,
    Strike,
    Underline,
    History,
} from 'tiptap-extensions'

export default {
    name: 'TextEditor',
    components: {
        EditorContent,
        EditorMenuBar,
    },
    data: function() {
        return {
            editor: new Editor({
                extensions: [
                    new PasteHandler(),
                    new EnterHandler(),
                    new BulletList(),
                    new HardBreak(),
                    new ListItem(),
                    new Bold(),
                    new Italic(),
                    new Strike(),
                    new Underline(),
                ],
                content: '',
            }),
            html: null,
            json: null,
        }
    },
    methods: {
        init: function() {
            this.editor.on('update', (e) => {
                this.html = e.getHTML()
                this.json = e.getJSON()
                this.$emit('update', this.json, this.html)
            })
        },
    },
    mounted: function() {
        this.$nextTick(this.init)
    },
    beforeDestroy: function() {
        this.editor.destroy()
    }
}
</script>

<style lang="scss">
@import '~styles/shared';
@import '~styles/vendor/tiptap/main';

.editor {
    max-width: 100%;
    margin: 0;
    background-color: rgba($white, 0.5);
    @include border-radius(10px);
    padding: $spacer;

    &__content {
        min-height: 20vh;
    }
}
</style>
