const UploadPdf = {
    name: 'uploadpdf',
    options: [{
            key: 'uploadpdf',
            type: 'uploadpdf',
            label: 'Upload Pdf',
            required: true,
        },
        {
            key: 'color',
            label: 'Colore del testo',
            required: false,
            type: 'color-picker',
        },
    ]
}

export default UploadPdf
