<template>
<div
    class="bank-transfer__form"
    ref="container"
>
    <ui-title
        ref="title"
        title="Paga tramite bonifico bancario"
        :is-column="true"
        tag="h4"
        font-size="h4"
        color="white"
    />
    <div
        class="bank-transfer__loader"
        ref="loader"
    >
        <div
            class="spinner-border text-white"
            role="status"
        >
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div
        ref="form"
        class="bank-transfer__form personal-data-form"
    >
        <div class="form-group row">
            <label
                for="name"
                class="col-12 personal-data-form__label"
                ref="name"
            >
                Conto intestato a:
                <span class="d-block personal-data-form__text">
                    Trasversale s.r.l.
                </span>
            </label>
        </div>
        <div class="form-group row">
            <label
                for="name"
                class="col-12 personal-data-form__label"
                ref="name"
            >
                IBAN:
                <span class="d-block personal-data-form__text">
                    IT02G0521603206000000093850
                </span>
            </label>
        </div>

        <div class="form-group row">
            <label
                for="name"
                class="col-12 personal-data-form__label"
                ref="name"
            >
                Dettagli:
                <span class="d-block personal-data-form__text">
                    Indicare nella causale la data e il nome del corso.<br>
                    Una volta effettuato il bonifico, si prega di contattarci via mail all'indirizzo
                    <a href="mailto:trasversale@trasversale.com">
                        trasversale@trasversale.com
                    </a>
                    indicando gli estremi del bonifico.
                </span>
            </label>

        </div>
    </div>
</div>
</template>

<script>
import {
    UiButton,
    UiParagraph,
    UiTitle
}
from '../ui'

export default {
    name: 'TeacherCardData',
    components: {
        UiButton,
        UiParagraph,
        UiTitle,
    },
    props: {
        item: {
            type: Object,
            default: function () {
                return {}
            },
        },
    },
    data: function () {
        return {
            master: null,
        }
    },
    methods: {
        initAnim: function () {
            let container = this.$refs.form
            let loader = this.$refs.loader
            let fields = container.getElementsByClassName('personal-data-form__label')

            this.master = new TimelineMax({
                paused: true,
                yoyo: true,
            })

            this.master.fromTo(loader, .6, {
                autoAlpha: 1,
            }, {
                autoAlpha: 0,
            }, 0)

            this.master.fromTo(container, .6, {
                opacity: 0,
                yPercent: 10,
            }, {
                opacity: 1,
                yPercent: 0,
            }, 0)

            this.master.staggerFromTo(fields, .6, {
                autoAlpha: 0,
                yPercent: 10,
                xPercent: -1,
                scale: 0.95,
            }, {
                autoAlpha: 1,
                yPercent: 0,
                xPercent: 0,
                scale: 1,
            }, .1, 0)

            this.master.progress(1).progress(0)

            this.$nextTick(() => {
                this.master.play()
            })
        },
        hideCart: function () {
            this.$emit('hide-cart')
        },
    },
    mounted: function () {
        this.$nextTick(() => {
            this.initAnim()
            // this.debug()
        })
    },
}
</script>

<style lang="scss">
@import '~styles/shared';

.personal-data-form {
    position: relative;

    &__label {
        color: $white;
    }

    &__fields {
        opacity: 0;
        overflow: hidden;
    }

    &__input {
        box-shadow: inset 0 1px 4px rgba(83, 88, 95, 0.4);
        background-image: linear-gradient(-225deg, rgba(255, 255, 255, 1), rgba(242, 242, 242, .8));
        background-repeat: repeat-x;
        border: 0 solid transparent;
        border-radius: 0 !important;

        &.not-valid {
            border: 2px solid $red;
        }

        &.valid {
            border: 2px solid $green;
        }
    }

    &__text {
        display: block;
        color: $white;

        & a {
            color: $white;
            text-decoration: underline;
            font-weight: $font-weight-bold;
        }
    }

    &__action {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    &--hidden {
        display: none;
    }
}
</style>
