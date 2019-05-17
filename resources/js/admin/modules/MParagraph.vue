<template lang="html">
    <div class="form">
        <div class="form-group row">
            <label for="title" class="col-md-2">Contenuto</label>
            <div class="col-md-10">
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
                                :class="{ 'is-active': isActive.paragraph() }"
                                @click="commands.paragraph">
                            Paragraph
                            </button>

                            <button
                                class="menubar__button"
                                :class="{ 'is-active': isActive.heading({ level: 1 }) }"
                                @click="commands.heading({ level: 1 })">
                            H1
                            </button>

                            <button
                                class="menubar__button"
                                :class="{ 'is-active': isActive.heading({ level: 2 }) }"
                                @click="commands.heading({ level: 2 })">
                            H2
                            </button>

                            <button
                                class="menubar__button"
                                :class="{ 'is-active': isActive.heading({ level: 3 }) }"
                                @click="commands.heading({ level: 3 })">
                            H3
                            </button>

                            <button
                                class="menubar__button"
                                :class="{ 'is-active': isActive.bullet_list() }"
                                @click="commands.bullet_list">
                            List
                            </button>

                            <button
                                class="menubar__button"
                                @click="commands.horizontal_rule">
                            HR
                            </button>
                        </div>
                    </editor-menu-bar>
                    <editor-content
                        class="editor__content"
                        :editor="editor">
                    </editor-content>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Editor, EditorContent, EditorMenuBar } from 'tiptap'
import {
    Blockquote,
    CodeBlock,
    HardBreak,
    Heading,
    HorizontalRule,
    OrderedList,
    BulletList,
    ListItem,
    TodoItem,
    TodoList,
    Bold,
    Code,
    Italic,
    Link,
    Strike,
    Underline,
    History,
} from 'tiptap-extensions'

export default {
    name: 'MParagraph',
    components: {
        EditorContent,
        EditorMenuBar,
    },
    data: function() {
        return {
            editor: new Editor({
                extensions: [
                    new Blockquote(),
                    new BulletList(),
                    new CodeBlock(),
                    new HardBreak(),
                    new Heading({ levels: [1, 2, 3] }),
                    new HorizontalRule(),
                    new ListItem(),
                    new OrderedList(),
                    new TodoItem(),
                    new TodoList(),
                    new Link(),
                    new Bold(),
                    new Code(),
                    new Italic(),
                    new Strike(),
                    new Underline(),
                    new History(),
                ],
                content: 'Ciao',
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
            })
        },
    },
    mounted: function() {
        this.$nextTick(this.init)
    },
}
</script>

<style lang="scss">
@import '~styles/shared';
@import '~styles/vendor/tiptap/main';


.ProseMirror [contenteditable="true"] {
    outline: none !important;
}
</style>
