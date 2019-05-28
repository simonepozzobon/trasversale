const Row = {
    name: 'row',
    options: [
        {
            key: 'columns',
            label: 'Numero di colonne',
            required: true,
            type: 'counter',
            options: {
                max: 12,
                min: 2,
            },
            default: 2,
        },
        {
            key: 'preview',
            label: 'Anteprima',
            require: false,
            type: 'row-preview',
            relatedKey: 'columns',

        }
    ],
}

export default Row
