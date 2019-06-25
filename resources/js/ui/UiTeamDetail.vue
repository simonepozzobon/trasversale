<template lang="html">
    <div class="team-detail" ref="container">
        <div class="team-detail__content">
            <div class="team-detail__close" @click="hide" ref="close">
                X
            </div>
            <div class="team-detail__name" ref="name">
                {{ member.name }}
            </div>
            <div class="team-detail__role" ref="role">
                {{ member.role }}
            </div>
            <div class="team-detail__desription" ref="description">
                <p v-html="member.description"></p>
            </div>
        </div>
    </div>
</template>

<script>
import {
    TweenMax
}
from 'gsap'

export default {
    name: 'UiTeamDetail',
    props: {
        member: {
            type: Object,
            default: function () {}
        }
    },
    data: function () {
        return {
            height: 0,
            width: 0,
            anim: null,
        }
    },
    methods: {
        getHeight: function (reset = false, playAsInit = false) {
            let container = this.$refs.container

            if (container.style.display === 'none') {
                container.style.display = ''
            }

            let rect = container.getBoundingClientRect()
            this.width = rect.width
            this.height = rect.height

            container.style.display = 'none'
            this.initAnim(reset, playAsInit)
        },
        initAnim: function (reset = false, playAsInit = false) {
            let el = this.$refs.container
            let close = this.$refs.close
            let name = this.$refs.name
            let role = this.$refs.role
            let description = this.$refs.description

            if (reset === false) {
                this.anim = new TimelineMax({
                    paused: true,
                    reversed: true,
                })

                this.anim.fromTo([close, name, role, description], .6, {
                    display: 'none',
                    autoAlpha: 0,
                }, {
                    display: 'block',
                    autoAlpha: 1,
                }, .6)

                this.anim.fromTo(el, .3, {
                    display: 'none',
                    autoAlpha: 0,
                }, {
                    display: 'block',
                    autoAlpha: 1,
                    ease: Sine.easeInOut,
                }, 0)

                this.anim.fromTo(el, .6, {
                    height: 0,
                }, {
                    height: this.height,
                    ease: Power2.easeInOut,
                }, .2)

                this.anim.fromTo(el, .6, {
                    width: 0,
                    padding: 0,
                }, {
                    padding: '2rem',
                    width: this.width,
                    ease: Power4.easeInOut,
                }, .1)

                this.anim.progress(1).progress(0)
                if (playAsInit) {
                    this.show()
                }
            }
            else {

                this.anim.kill()
                TweenMax.set([el, close, name, role, description], {
                    clearProps: 'all'
                })
                this.getHeight(false, playAsInit)
            }
        },
        show: function () {
            this.anim.progress(0).play()
        },
        hide: function () {
            this.anim.progress(1).reverse()
        }
    },
    mounted: function () {
        this.getHeight()
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.team-detail {
    position: absolute;
    left: 50%;
    top: 50%;
    width: 95%;
    height: 110%;
    background-color: rgba(lighten($primary, 15), .96);
    transform: translate(-50%, -50%);
    padding: $spacer * 2;

    &__close {
        color: $white;
        position: absolute;
        right: $spacer * 2;
        top: $spacer * 2;
        cursor: pointer;
        font-size: $font-size-lg;
    }

    &__name {
        color: $white;
        font-size: $font-size-lg;
        text-transform: uppercase;
        margin-bottom: $spacer / 2;
    }

    &__role {
        margin-bottom: $spacer * 2;
    }
}
</style>
