const SubscriberEdit = {
    name: 'subscriber-edit',
    options: [{
            type: 'section-head',
            label: 'Pagamento',
            options: {
                paddingTop: false,
            }
        },
        {
            key: 'payment_type_id',
            label: 'Tipo di pagamento',
            required: true,
            type: 'select',
            options: [{
                    value: 1,
                    label: 'Carta'
                },
                {
                    value: 2,
                    label: 'Bonifico'
                },
                {
                    value: 3,
                    label: 'Carta Docente'
                },
                {
                    value: 4,
                    label: 'Richiesta di informazioni'
                },
            ],
            default: 1,
        },
        {
            key: 'payment_status_id',
            label: 'Stato del pagamento',
            required: true,
            type: 'select',
            options: [{
                    value: 1,
                    label: 'Pagato'
                },
                {
                    value: 2,
                    label: 'Da verificare'
                },
                {
                    value: 3,
                    label: 'Non Pagato'
                },
                {
                    value: 4,
                    label: 'Ordine Non Completato'
                },
            ],
            default: 1,
        },
        {
            type: 'section-head',
            label: 'Dati Personali'
        },
        {
            key: 'type',
            label: 'Tipo ',
            type: 'select',
            options: [{
                    value: 'professionista',
                    label: 'Persona fisica con partita iva'
                },
                {
                    value: 'azienda',
                    label: 'Società'
                },
                {
                    value: 'privato',
                    label: 'Persona fisica senza partita iva'
                },
            ],
            default: 'privato',
        },
        {
            key: 'language',
            label: 'Lingua',
            type: 'select',
            options: [{
                    value: 'italia',
                    label: 'Italia'
                },
                {
                    value: 'estero',
                    label: 'Estero'
                },
            ],
            default: 'italia',
        },
        {
            key: 'ragione_sociale',
            label: 'Ragione Sociale',
            required: true,
            type: 'text',
        },
        {
            key: 'name',
            label: 'Nome',
            required: true,
            type: 'text',
        },
        {
            key: 'surname',
            label: 'Cognome',
            required: true,
            type: 'text',
        },
        {
            key: 'email',
            label: 'Email',
            required: true,
            type: 'text',
        },
        {
            key: 'pec',
            label: 'Pec',
            required: true,
            type: 'text',
        },
        {
            key: 'phone',
            label: 'Telefono',
            required: true,
            type: 'text',
        },
        {
            key: 'vat',
            label: 'P.IVA',
            required: true,
            type: 'text',
        },
        {
            key: 'codice_destinatario',
            label: 'Codice Destinatario',
            required: true,
            type: 'text',
        },
        {
            key: 'cf',
            label: 'Codice Fiscale',
            required: true,
            type: 'text',
        },
        {
            type: 'section-head',
            label: 'Ordine'
        },
        {
            key: 'address',
            label: 'Indirizzo',
            required: true,
            type: 'text',
        },
        {
            key: 'city',
            label: 'Città',
            required: true,
            type: 'text',
        },
        {
            key: 'province',
            label: 'Provincia',
            required: true,
            type: 'text',
        },
        {
            key: 'postal_code',
            label: 'CAP',
            required: true,
            type: 'text',
        },
    ],
}

export default SubscriberEdit
