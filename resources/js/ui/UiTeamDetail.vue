<template>
<div
    class="team-detail"
    ref="container"
>
    <div class="team-detail__content">
        <div
            class="team-detail__close"
            @click="hide"
            ref="close"
        >
            <div class="team-detail__cross">
                +
            </div>
        </div>
        <div
            class="team-detail__name"
            ref="name"
        >
            {{ member.name }}
        </div>
        <div
            class="team-detail__role"
            ref="role"
        >
            {{ member.role }}
        </div>
        <div
            class="team-detail__desription"
            ref="description"
        >
            <p v-html="member.description"></p>
        </div>
    </div>
</div>
</template>

<script>
import {
    gsap
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
            if (this.$refs.container) {
                let container = this.$refs.container

                if (container.style.display === 'none') {
                    container.style.display = ''
                }

                let rect = container.getBoundingClientRect()
                this.width = rect.width
                this.height = rect.height

                container.style.display = 'none'
                this.initAnim(reset, playAsInit)
            }
        },
        initAnim: function (reset = false, playAsInit = false) {
            let el = this.$refs.container
            let close = this.$refs.close
            let name = this.$refs.name
            let role = this.$refs.role
            let description = this.$refs.description
            //
            if (reset === false) {
                gsap.set([el, close, name, role, description], {
                    clearProps: 'all'
                })

                this.anim = gsap.timeline({
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
                    ease: 'sine.inOut',
                }, 0)

                this.anim.fromTo(el, .6, {
                    height: 0,
                }, {
                    height: this.height,
                    ease: 'power2.inOut',
                }, .2)

                this.anim.fromTo(el, .6, {
                    width: 0,
                    padding: 0,
                }, {
                    padding: '2rem',
                    width: this.width,
                    ease: 'power4.inOut',
                }, .1)

                this.anim.progress(1).progress(0)

                if (playAsInit) {
                    this.$nextTick(() => {
                        this.show()
                    })
                }
            }
            else {

                this.anim.kill()
                gsap.set([el, close, name, role, description], {
                    clearProps: 'all'
                })
                this.getHeight(false, playAsInit)
            }
        },
        show: function () {
            if (this.anim) {
                this.anim.play()
            }
        },
        hide: function () {
            if (this.anim) {
                this.anim.reverse()
            }
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
    width: 100%;
    height: 100%;
    background-color: rgba(lighten($primary, 15), .93);
    transform: translate(-50%, -50%);
    z-index: 2;

    &__content {
        padding: $spacer * 2;
    }

    &__close {
        color: $white;
        position: absolute;
        right: $spacer * 2;
        cursor: pointer;
    }

    &__cross {
        position: relative;
        font-size: $h2-font-size;
        font-weight: $font-weight-bold;
        line-height: 0.5;
        transform: rotate(-45deg);
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
