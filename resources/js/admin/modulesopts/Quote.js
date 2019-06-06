const Quote = {
    name: 'quote',
    options: [
        {
            key: 'content',
            label: 'Citazione',
            required: true,
            type: 'text',
        },
        {
            key: 'source',
            label: 'Fonte',
            required: false,
            type: 'text',
        },
    ],
}

export default Quote