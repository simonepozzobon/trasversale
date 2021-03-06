const Corsi = {
    title: 'Corsi',
    table: 'products',
    model: 'product',
    slug: 'corsi',
    create: 'Crea Corso',
    single: 'Corso',
    fields: [{
            key: 'id',
            label: 'ID',
            sortable: true,
        },
        {
            key: 'guests_total',
            label: 'Iscritti',
            sortable: true,
        },
        {
            key: 'guests_confirmed',
            label: 'Confermati',
            sortable: true,
        },
        {
            key: 'subscribers_tools',
            label: 'Gestisci iscrizioni',
            sortable: true,
        },
        {
            key: 'price',
            label: 'Prezzo',
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
