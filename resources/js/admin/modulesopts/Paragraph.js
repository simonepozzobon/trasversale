const Paragraph = {
    name: 'paragraph',
    options: [
        {
            key: 'color',
            required: false,
            type: 'color-picker',
        },
        {
            key: 'content',
            required: true,
            type: 'wysiwyg',
        },
        {
            key: 'bg_color',
            required: false,
            type: 'color-picker',
        },
    ],
}

export default Paragraph
