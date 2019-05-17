const Grid = {
    name: 'grid',
    options: [
        {
            key: 'title',
            required: true,
            type: 'text',
        },
        {
            key: 'type',
            required: true,
            type: 'select',
            childrens: [
                { value: 'packery', label: 'Mosaico' },
                { value: 'simple', label: 'Griglia' },
            ],
            default: 'packery',
        },
    ],
}

export default Grid
