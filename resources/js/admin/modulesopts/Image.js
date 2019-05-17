const Image = {
    name: 'image',
    options: [
        {
            key: 'alt',
            label: 'Titolo Immagine',
            info: 'Non sarà visibile ma è utile per il SEO',
            required: true,
            type: 'text',
        },
        {
            key: 'src',
            label: 'Immagine',
            required: true,
            accept: 'image/*',
            type: 'file-input',
        },
        {
            key: 'preview',
            type: 'preview',
            parent: 'src',
            mime: 'image'
        }
    ],
}

export default Image
