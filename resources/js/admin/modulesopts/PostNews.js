const PostNews = {
    name: 'post-news',
    options: [{
            key: 'title',
            label: 'Titolo',
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
            key: 'published_at',
            label: 'Data di pubblicazione',
            type: 'date-picker',
            required: true,
        },
    ],
}

export default PostNews
