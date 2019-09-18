export default {
    name: 'Icon',
    props: {
        width: {
            type: String,
            default: '32px'
        },
        color: {
            type: String,
            default: null,
        },
        hoverable: {
            type: Boolean,
            default: false,
        },
        hoverColor: {
            type: String,
            default: 'primary',
        },
        hasHexColor: {
            type: Boolean,
            default: false,
        },
    },
    data: function () {
        return {
            paths: null,
        }
    },
    watch: {
        color: function () {
            this.setColor()
        },
    },
    computed: {
        colorClass: function () {
            if (this.color && !this.hasHexColor) {
                return 'ui-icon--' + this.color
            }

        },
        hoverableClass: function () {
            if (this.hoverable) {
                return 'ui-icon--hoverable ui-icon--hover-' + this.hoverColor
            }
        }
    },
    methods: {
        clicked: function () {
            if (this.hoverable) {
                this.$emit('click')
            }
        },
        getPaths: function () {
            this.paths = this.$el.getElementsByClassName('ui-icon__path')
            this.setColor()
        },
        setColor: function () {
            if (this.hasHexColor) {
                for (let i = 0; i < this.paths.length; i++) {
                    this.paths[i].style.fill = this.color
                }
            }
        },
    },
    mounted: function () {
        this.getPaths()
    },
}
