const Video = {
    name: 'video',
    options: [
        {
            key: 'title',
            label: 'Titolo',
            required: false,
            info: 'Non sarà visibile ma è utile per il SEO',
            type: 'text',
        },
        {
            key: 'host',
            label: 'Hosting',
            required: true,
            type: 'select',
            options: [
                { value: 'youtube', label: 'YouTube'},
                { value: 'vimeo', label: 'Vimeo'},
            ],
            default: 'youtube',
        },
        {
            key: 'url',
            label: 'Url del video',
            required: true,
            type: 'text',
        },
        // {
        //     key: 'preview',
        //     parent: 'url',
        //     type: 'preview',
        //     mime: 'video-url',
        // },
    ],
}

export default Video
