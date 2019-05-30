//https://github.com/scrumpy/tiptap/issues/104

import { Extension, Plugin, ExtensionManager } from 'tiptap'
import { autoJoin } from 'prosemirror-commands'

export default class PasteHandler extends Extension {
    get name() {
        return 'paste_handler'
    }

    get plugins() {
        return [
            new Plugin({
                props: {
                    handlePaste: (view, event, slice) => {
                        const { schema, doc, tr } = view.state
                        const hard_break = schema.nodes.hard_break

                        const fragments = slice.content
                        return true
                        // console.log(slice);
                        // fragments.forEach((node, offset, index) => {
                        //     if (node.childCount > 0) {
                        //     }
                        // })
                    },
                },
            }),
        ]
    }
}
