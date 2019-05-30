<template>
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
        <editor-menu-bubble
            class="menububble"
            :editor="editor"
            @hide="hideLinkMenu"
            v-slot="{ commands, isActive, getMarkAttrs, menu }"
        >
            <div
                class="menububble"
                :class="{ 'is-active': menu.isActive }"
                :style="`left: ${menu.left}px; bottom: ${menu.bottom}px;`"
            >

            <form class="menububble__form" v-if="linkMenuIsActive" @submit.prevent="setLinkUrl(commands.link, linkUrl)">
                <input class="menububble__input" type="text" v-model="linkUrl" placeholder="https://" ref="linkInput" @keydown.esc="hideLinkMenu"/>
                <button class="menububble__button" @click="setLinkUrl(commands.link, null)" type="button">
                    Elimina
                </button>
            </form>

            <template v-else>
                <button
                    class="menububble__button"
                    @click="showLinkMenu(getMarkAttrs('link'))"
                    :class="{ 'is-active': isActive.link() }"
                >
                    <span>{{ isActive.link() ? 'Modifica Link' : 'Aggiungi Link'}}</span>
                </button>
            </template>

            </div>
        </editor-menu-bubble>
        <editor-content
            class="editor__content"
            :editor="editor">
        </editor-content>
    </div>
</template>

<script>
// import EnterHandler from './editor/EnterHandler'
// import PasteHandler from './editor/PasteHandler'

import {
    Editor,
    EditorContent,
    EditorMenuBar,
    EditorMenuBubble,
} from 'tiptap'


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
    name: 'TextEditor',
    components: {
        EditorContent,
        EditorMenuBar,
        EditorMenuBubble,
    },
    props: {
        initial: {
            type: String,
            default: null,
        },
    },
    data: function() {
        return {
            editor: null,
            html: null,
            json: null,
            linkUrl: null,
            linkMenuIsActive: false,
        }
    },
    methods: {
        init: function() {
            this.editor = new Editor({
                extensions: [
                    // new PasteHandler(),
                    // new EnterHandler(),
                    new Blockquote(),
                    new BulletList(),
                    new CodeBlock(),
                    new HardBreak(),
                    new Heading({
                        levels: [1, 2, 3]
                    }),
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
                content: this.initial ? this.initial : '',
            })

            // console.log(this.initial);

            this.editor.on('update', (e) => {
                this.html = e.getHTML()
                this.json = e.getJSON()
                this.$emit('update', this.json, this.html)
                // console.log('updated');
            })
        },
        showLinkMenu: function(attrs) {
            this.linkUrl = attrs.href
            this.linkMenuIsActive = true
            this.$nextTick(() => {
                this.$refs.linkInput.focus()
            })
        },
        hideLinkMenu: function() {
            this.linkUrl = null
            this.linkMenuIsActive = false
        },
        setLinkUrl: function(command, url) {
            command({ href: url })
            this.hideLinkMenu()
            this.editor.focus()
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

    // &__content {
    //     min-height: 20vh;
    // }
}
</style>
