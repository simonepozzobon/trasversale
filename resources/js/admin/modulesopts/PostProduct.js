const PostProduct = {
    name: 'post-products',
    options: [
        {
            key: 'title',
            label: 'Titolo',
            required: true,
            type: 'text',
        },
        {
            key: 'price',
            label: 'Prezzo',
            required: true,
            type: 'text',
        },
        {
            key: 'slug',
            label: 'Slug',
            info: 'sarà l\'indirizzo del post',
            required: true,
            type: 'text',
        },
        {
            key: 'thumb',
            label: 'Immagine',
            required: true,
            accept: 'image/*',
            type: 'file-input',
        },
        {
            key: 'preview',
            type: 'preview',
            parent: 'thumb',
            mime: 'image'
        },
        {
            key: 'category',
            label: 'Categoria',
            type: 'dynamic-select',
            required: true,
            request: {
                url: '/api/admin/get-categories',
                handleResponse: response => {
                    return response.data.categories.map(category => {
                        return {
                            value: category.id,
                            label: category.title
                        }
                    })
                }
            },
        },
        {
            key: 'start_at',
            label: 'Data di inizio',
            type: 'date-picker',
            required: true,
        },
        {
            key: 'end_at',
            label: 'Data di fine',
            type: 'date-picker',
            required: true,
        },
        {
            key: 'hours',
            label: 'Durata del corso',
            info: 'La durate è espressa in ore',
            type: 'counter',
            default: 0,
            required: true,
        },
        {
            key: 'address',
            label: 'Indirizzo',
            type: 'text',
            required: true,
        },
    ],
}

export default PostProduct
