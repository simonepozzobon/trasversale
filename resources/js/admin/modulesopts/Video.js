const Video = {
    name: 'video',
    options: [
        {
            key: 'title',
            required: false,
            type: 'text',
        },
        {
            key: 'host',
            required: true,
            type: 'select',
            childrens: [
                { value: 'youtube', label: 'YouTube'},
                { value: 'vimeo', label: 'Vimeo'},
            ],
            default: 'youtube',
        },
        {
            key: 'url',
            required: true,
            type: 'text',
        },
        {
            key: 'preview',
            parent: 'url',
            type: 'video'
        }
    ],
}

export default Video
