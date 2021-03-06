const PostProduct = {
    name: 'post-products',
    options: [{
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
            key: 'vat_included',
            label: 'Prezzo iva inclusa',
            required: false,
            default: false,
            type: 'switch',
        },
        {
            key: 'vat',
            label: 'Iva',
            required: true,
            type: 'text',
            default: 0,
            info: 'in percentuale, inserire 22 per 22%',
        },
        {
            key: 'teacher_card_payment',
            label: 'Pagamento con carta del docente',
            required: false,
            default: false,
            type: 'switch',
        },
        {
            key: 'has_limited_guests',
            label: 'Ha posti limitati',
            required: false,
            default: false,
            type: 'switch',
        },
        {
            key: 'guests_total',
            type: 'counter',
            label: 'Posti totali',
            required: true,
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
            type: 'text',
            default: 0,
            required: true,
        },
        {
            key: 'address',
            label: 'Indirizzo',
            type: 'text',
            required: true,
        },
        {
            key: 'forwho',
            label: 'Destinatari',
            info: 'Inserire solo testo, non supporta nuove linee a capo o html',
            type: 'wysiwyg',
            options: {
                menubar: false,
                bubble: false,
                html: false,
            },
            required: true,
        },
    ],
}

export default PostProduct
