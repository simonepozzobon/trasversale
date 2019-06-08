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
        }
    ],
}

export default PostProduct
