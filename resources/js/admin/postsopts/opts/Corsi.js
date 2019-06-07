const Corsi = {
    title: 'Corsi',
    table: 'products',
    slug: 'corsi',
    create: 'Crea Corso',
    single: 'Corso',
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

export default Corsi
