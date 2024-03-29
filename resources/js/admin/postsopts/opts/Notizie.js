const Notizie = {
    title: 'Notizie',
    table: 'news',
    model: 'news',
    slug: 'notizie',
    create: 'Crea Notizia',
    single: 'Notizia',
    fields: [
        {
            key: 'id',
            label: 'ID',
            sortable: true,
        },
        {
            key: 'title',
            label: 'Titolo',
            sortable: true,
        },
        {
            key: 'thumb',
            label: 'Immagine',
            sortable: false,
        }
    ]
}

export default Notizie
