<template lang="html">
    <ui-modal
        ref="modal"
        :title="this.title"
        size="lg"
        @send="upload">

        <ui-loader ref="loader"/>

        <div ref="message">
            <h1 class="text-success text-center">{{ successMessage }}</h1>
        </div>
        
        <div ref="form" class="">
            <p class="mb-5">
                <slot></slot>
            </p>
            <hr class="mb-5">
            <ui-container
                ref="container"
                v-for="(item, i) in this.fields"
                :key="item.key">
                <ui-form-title
                    :title="item.title"
                    v-if="item.type == 'title'"/>

                <ui-form-group-text
                    v-else-if="item.type == 'input'"
                    ref="input"
                    :idx="i"
                    :name="item.name"
                    :title="item.title"
                    :input-type="item.input"
                    :is-mandatory="item.isMandatory"
                    @changed="setValue">
                    {{ item.help }}
                </ui-form-group-text>

                <ui-dynamic-input
                    v-else-if="item.type == 'dynamic'"
                    ref="dynamic"
                    :idx="i"
                    :name="item.name"
                    :global-error="item.error"
                    :is-mandatory="item.isMandatory"
                    :fields="item.fields"
                    :initial-obj="item.initialObj"
                    @changed="setDynamicValue"
                    />

                <ui-form-checkbox
                    v-else-if="item.type == 'checkbox'"
                    ref="checkbox"
                    :idx="i"
                    :title="item.title"
                    :name="item.name"
                    :help="item.help"
                    @changed="setCheckbox"
                    />

                <hr class="my-5" v-else>

            </ui-container>
            <div class="mt-5 alert alert-danger" style="display: none" role="alert" ref="alert">
                <h4><strong>Error!</strong></h4>
                <span v-html="this.errorMsg"></span>
            </div>
        </div>

    </ui-modal>
</template>

<script>
import axios from 'axios'
import { UiContainer, UiDynamicInput, UiFormCheckbox, UiFormGroupText, UiFormTitle, UiLoader, UiModal } from '../../ui'
import { TweenMax } from 'gsap'

export default {
    name: 'DynamicForm',
    components: {
        UiContainer,
        UiDynamicInput,
        UiFormCheckbox,
        UiFormGroupText,
        UiFormTitle,
        UiLoader,
        UiModal,
    },
    props: {
        title: {
            type: String,
            default: 'Titolo'
        },
        fields: {
            type: Array,
            default: function() {}
        },
        initialObj: {
            type: Array,
            default: function() {}
        },
        api: {
            type: String,
            default: 'nulllo'
        },
        successMessage: {
            type: String,
            default: null,
        }
    },
    data: function() {
        return {
            obj: {},
            hasError: null,
            missingFields: 0,
            errorMsg: null,
            cacheMsg: null,
        }
    },
    watch: {
        hasError: function(status) {
            let el = this.$refs.alert
            let master = new TimelineMax({
                paused: true
            })

            if (status == true) {
                master.fromTo(el, .1, {
                    display: 'none',
                }, {
                    display: 'block'
                }, 0)

                master.fromTo(el, .6, {
                    autoAlpha: 0,
                }, {
                    autoAlpha: 1
                })
            } else {
                master.fromTo(el, .1, {
                    display: 'block'
                }, {
                    display: 'none',
                }, 0)

                master.fromTo(el, .1, {
                    autoAlpha: 1
                }, {
                    autoAlpha: 0,
                })
            }

            master.play()
        }
    },
    methods: {
        setValue: function(value, field, idx) {
            this.obj[field] = value // assegno il valore
            this.$refs.container[idx].emit('check', field) // lancio l'evento per verificare il campo
        },
        setDynamicValue: function(value, field, idx) {
            let json = JSON.stringify(value)
            this.obj[field] = json // assegno il valore
        },
        setCheckbox: function(value, field, idx) {
            this.obj[field] = value
            this.$refs.container[idx].emit('check', field) // lancio l'evento per verificare il campo
        },
        show: function() {
            if (this.obj && this.fields) {
                this.$refs.modal.show()
            }
        },
        upload: function() {
            this.$nextTick(() => {
                this.hasError = false
                this.errorMsg = 'Please check '
                this.cacheMsg = '<br>',
                this.missingFields = 0

                let data = new FormData()
                let inputs = this.$refs.input
                let dynamics = this.$refs.dynamic ? this.$refs.dynamic : []
                let checkboxs = this.$refs.checkbox ? this.$refs.checkbox : []

                let check, idx, cacheObj
                // iterate trough obj key to prepare the FormData
                for (let key in this.obj) {
                    if (this.obj.hasOwnProperty(key)) {

                        // select the child component to check if is mandatory and ready to send
                        idx = inputs.findIndex(item => item.name == key)
                        // if is consistent
                        if (idx > -1) {

                            // check it's value
                            check = inputs[idx].globalCheck()

                            // if it's not valid
                            if (!check) {
                                inputs[idx].check()

                                // prepare the error message
                                this.errorMsg = this.errorMsg + '<b>' + inputs[idx].title + '</b>' + ', '
                                this.hasError = true
                                this.missingFields++
                            }

                        } else {
                            idx = dynamics.findIndex(item => item.name == key)
                            if (idx > -1) {
                                let check = dynamics[idx].globalCheck()
                                if (!check && dynamics[idx].table.rows.length == 0) {
                                    this.cacheMsg = this.cacheMsg + '<b>' + dynamics[idx].globalError + '</b>' + '<br>'
                                    this.hasError = true
                                    this.missingFields++
                                } else if (!check){
                                    this.hasError = true
                                    this.missingFields++
                                } else {
                                    // must use this to set the value before watch function
                                    this.obj[key] = JSON.stringify(dynamics[idx].value)
                                }
                            } else {
                                idx = checkboxs.findIndex(item => item.name == key)
                                if (idx > -1) {
                                    let check = checkboxs[idx].globalCheck()
                                    if (!check) {
                                        checkboxs[idx].check()
                                        this.errorMsg = this.errorMsg + '<b>' + checkboxs[idx].title + '</b>' + ', '
                                        this.hasError = true
                                        this.missingFields++
                                    }
                                }
                            }
                        }

                        // add the value to data
                        data = this.setData(data, key, this.obj[key])
                    }
                }
                this.sendRequest(data)
            })

        },
        sendRequest: function(data) {
            // close the error message
            if (this.hasError == true) {
                if (this.missingFields > 1) {
                    this.errorMsg = this.errorMsg + ' these fields are mandatory!'
                } else {
                    this.errorMsg = this.errorMsg + 'this field is mandatory!'
                }
                if (this.cacheMsg) {
                    this.errorMsg = this.errorMsg + this.cacheMsg
                }
            } else {
                // send the request
                this.hideForm()
                this.showLoader()
                axios.post(this.api, data).then(response => {
                    this.hideLoader()
                    this.showSuccess()
                }).catch(err => {
                    this.hideLoader()
                    this.showForm()
                })
            }

        },
        setData: function(data, field, value) {
            if (value) {
                data.append(field, value)
                return data
            }
            return data
        },
        clearForm: function() {
            let inputs = this.$refs.input
            for (let i = 0; i < inputs.length; i++) {
                inputs[i].clear()
            }
        },
        init: function() {
            let item = this.initialObj
            let obj = new Object()

            for (var i = 0; i < item.length; i++) {
                obj[item[i]] = null
            }
            this.obj = obj
        },
        showSuccess: function() {
            let master = new TimelineMax({
                paused: true,
            })

            master.fromTo(this.$refs.message, .6, {
                autoAlpha: 0,
                display: 'none',
            }, {
                autoAlpha: 1,
                display: 'block'
            })

            master.to(this.$refs.message, .6, {
                delay: 3,
                autoAlpha: 0,
                display: 'none',
                onComplete: () => {
                    this.hideModal()
                    this.clearForm()
                    this.showForm()
                }
            })

            master.play()
        },
        hideMessage: function() {
            this.$refs.message.style.display = 'none'
        },
        showLoader: function() {
            this.$refs.loader.show()
        },
        hideLoader: function() {
            this.$refs.loader.hide()
        },
        hideModal: function() {
            this.$refs.modal.hide()
        },
        showForm: function() {
            let master = new TimelineMax({ paused: true, })
            master.fromTo(this.$refs.form, .3, {
                autoAlpha: 0,
                display: 'none',
            }, {
                autoAlpha: 1,
                display: 'block',
            })
            master.play()
        },
        hideForm: function() {
            let master = new TimelineMax({ paused: true, })
            master.fromTo(this.$refs.form, .3, {
                autoAlpha: 1,
                display: 'block',
            }, {
                autoAlpha: 0,
                display: 'none',
            })
            master.play()
        },
    },
    mounted: function() {
        this.hideMessage()
        this.init()
        this.hideLoader()
    }
}
</script>

<style lang="scss" scoped>
</style>
