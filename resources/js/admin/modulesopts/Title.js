const Title = {
    name: 'title',
    options: [{
            key: 'content',
            label: 'Titolo',
            required: true,
            type: 'text',
        },
        {
            key: 'fontSize',
            label: 'Dimensione del testo',
            required: false,
            type: 'select',
            options: [{
                    value: 'h1',
                    label: 'H1'
                },
                {
                    value: 'h2',
                    label: 'H2'
                },
                {
                    value: 'h3',
                    label: 'H3'
                },
                {
                    value: 'h4',
                    label: 'H4'
                },
                {
                    value: 'h5',
                    label: 'H5'
                },
                {
                    value: 'h6',
                    label: 'H6'
                },
            ],
            default: 'h2',
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
