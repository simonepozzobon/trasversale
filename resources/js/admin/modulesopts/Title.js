const Title = {
    name: 'title',
    options: [
        {
            key: 'content',
            label: 'Titolo',
            required: true,
            type: 'text',
        },
        {
            key: 'fontSize',
            label: 'Dimensione del testo',
            required: false,
            type: 'text',
        },
        {
            key: 'color',
            label: 'Colore del testo',
            required: false,
            type: 'color-picker',
        },
        {
            key: 'isColumn',
            label: 'Allinea titolo a sinistra',
            required: false,
            type: 'switch',
        },
        {
            key: 'uppercase',
            label: 'Tutto maiuscolo',
            required: false,
            type: 'switch',
        },
    ],
}

export default Title
