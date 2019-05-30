const Paragraph = {
    name: 'paragraph',
    options: [
        {
            key: 'content',
            label: 'Contenuto',
            required: true,
            type: 'wysiwyg',
        },
        {
            key: 'color',
            label: 'Colore del testo',
            required: false,
            type: 'color-picker',
        },
        {
            key: 'bg_color',
            label: 'Colore dello sfondo',
            required: false,
            type: 'color-picker',
        },
    ],
}

export default Paragraph
