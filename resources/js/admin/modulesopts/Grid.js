const Grid = {
    name: 'grid',
    options: [
        {
            key: 'grid',
            type: 'grid',
            label: 'Griglia',
            required: true,
        }
    ]
    // options: [
    //     {
    //         key: 'title',
    //         label: 'Titolo',
    //         info: 'Non sarà visibile, serve solo ad identificarla',
    //         required: true,
    //         type: 'text',
    //     },
    //     {
    //         key: 'type',
    //         required: true,
    //         label: 'Tipologia',
    //         type: 'select',
    //         options: [
    //             { value: 'packery', label: 'Mosaico' },
    //             { value: 'simple', label: 'Classica' },
    //         ],
    //         default: 'packery',
    //     },
    //     {
    //         key: 'elements',
    //         required: true,
    //         type: 'multiple',
    //         childrens: [
    //             {
    //                 key: 'model',
    //                 required: true,
    //                 label: 'Sorgente Post',
    //                 type: 'select',
    //                 info: 'Se si seleziona Manuale, i post non si aggiorneranno automaticamente',
    //                 options : [
    //                     { value: 'manual', label: 'Manuale' },
    //                     { value: 'news', label: 'Notizie' },
    //                     { value: 'products', label: 'Prodotti' },
    //                     { value: 'last-mix', label: 'Ultimi Prodotti e Notizie' },
    //                 ],
    //                 default: 'last-mix',
    //             },
    //             {
    //                 key: 'post_count',
    //                 required: false,
    //                 label: 'Numero di post',
    //                 type: 'counter',
    //                 relatedKey: 'model',
    //                 default: 10,
    //                 visible: related => {
    //                     if (related == 'last-mix') {
    //                         return true
    //                     }
    //                     return false
    //                 },
    //             },
    //             {
    //                 key: 'blocks',
    //                 required: true,
    //                 label: 'Elementi',
    //                 type: 'post-select',
    //                 relatedKey: ['model', 'post_count'],
    //                 visible: related => {
    //                     if (related && related != 'last-mix') {
    //                         return true
    //                     }
    //                     return false
    //                 }
    //             }
    //         ],
    //     }
    // ],
}

export default Grid
