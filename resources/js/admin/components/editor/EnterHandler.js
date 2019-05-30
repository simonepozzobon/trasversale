//https://github.com/scrumpy/tiptap/issues/104

import { Extension, Plugin, ExtensionManager } from 'tiptap'

export default class EnterHandler extends Extension {
    get name() {
        return 'enter_handler'
    }

    get plugins() {
        return [
            new Plugin({
                props: {
                    handleKeyDown: (view, event) => {
                        if (event.key === 'Enter' && !event.shiftKey) {
                            const { schema, doc, tr } = view.state

                            const hard_break = schema.nodes.hard_break

                            const transaction = tr.replaceSelectionWith(hard_break.create()).scrollIntoView()
                            view.dispatch(transaction)
                            return true
                        }
                        return false
                    },
                },
            }),
        ]
    }
}
