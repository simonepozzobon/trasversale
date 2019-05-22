const Grid = {
    name: 'grid',
    options: [
        {
            key: 'title',
            label: 'Titolo',
            info: 'Non sarà visibile, serve solo ad identificarla',
            required: true,
            type: 'text',
        },
        {
            key: 'type',
            required: true,
            label: 'Tipologia',
            type: 'select',
            options: [
                { value: 'packery', label: 'Mosaico' },
                { value: 'simple', label: 'Classica' },
            ],
            default: 'packery',
        },
        {
            key: 'elements',
            required: true,
            type: 'multiple',
            childrens: [
                {
                    key: 'model',
                    required: true,
                    label: 'Sorgente Post',
                    type: 'select',
                    info: 'Se si seleziona Manuale, i post non si aggiorneranno automaticamente',
                    options : [
                        { value: 'manual', label: 'Manuale' },
                        { value: 'news', label: 'Notizie' },
                        { value: 'products', label: 'Prodotti' },
                        { value: 'last', label: 'Ultimi Prodotti e Notizie' },
                    ],
                    default: 'manual',
                },
                {
                    key: 'items',
                    required: true,
                    label: 'Elementi',
                    type: 'post-select',
                    relatedKey: 'model',
                }
            ],
        }
    ],
}

export default Grid
