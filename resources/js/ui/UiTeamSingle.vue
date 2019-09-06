<template>
<div
    :class="[
        gridColClass
    ]"
    class="team-member"
>
    <div
        class="team-member__avatar team-member-avatar"
        @click.prevent="openMember"
    >
        <div class="team-member-avatar__avatar">
            <img
                :src="member.img"
                :alt="member.name"
                class="team-member-avatar__img"
            >
            <div class="team-member-avatar__overlay">
                <div class="team-member-avatar__overlay-text">
                    +
                </div>
            </div>
        </div>
    </div>

    <div class="team-member__info team-member-info">
        <div class="team-member-info__name">
            {{ member.name }}
        </div>
        <div class="team-member-info__role">
            {{ member.role }}
        </div>
        <div class="team-member-info__social">
            <div v-for="(social, i, key) in this.socials">
                <a
                    :href="social.value"
                    v-if="social.name == 'twitter'"
                >
                    <twitter />
                </a>
                <a
                    :href="social.value"
                    v-else-if="social.name == 'facebook'"
                >
                    <facebook />
                </a>
                <a
                    :href="social.value"
                    v-else-if="social.name == 'linkedin'"
                >
                    <linkedin />
                </a>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import {
    Facebook,
    Linkedin,
    Twitter
}
from '../icons'

export default {
    name: 'UiTeamSingle',
    components: {
        Facebook,
        Linkedin,
        Twitter,
    },
    props: {
        gridCol: {
            type: Number,
            default: 0,
        },
        member: {
            type: Object,
            default: function () {
                return {
                    name: null,
                    role: null,
                    social: [],
                }
            }
        }
    },
    watch: {
        gridCol: function () {
            this.$nextTick(() => this.setAvatarWidth())
        },
        member: {
            handler: function () {
                this.$nextTick(() => this.setAvatarWidth())
            },
            deep: true
        },
    },
    computed: {
        socials: function () {
            let newlist = []
            let social = this.member.social
            if (social) {
                for (var i = 0; i < social.length; i++) {
                    let temp = social[i]
                    for (let key in temp) {
                        if (temp.hasOwnProperty(key)) {
                            let obj = {
                                name: key,
                                value: temp[key]
                            }
                            newlist.push(obj)
                        }
                    }
                }
            }
            return newlist
        },
        gridColClass: function () {
            if (this.gridCol > 0) {
                return 'col-md-' + this.gridCol
            }

            return 'col'
        }
    },
    methods: {
        setAvatarWidth: function () {
            // let container = this.$refs.member
            // let avatar = this.$refs.avatar
            // if (container && avatar) {
            //     let width = container.getBoundingClientRect().width
            //     avatar.style.width = width + 'px'
            //     avatar.style.height = width + 'px'
            //     avatar.style.backgroundImage = 'url(' + this.member.img + ')'
            // }

        },
        openMember: function () {
            this.$emit('open-member', this.member)
        }
    },
    mounted: function () {
        this.$nextTick(() => this.setAvatarWidth())
    }
}
</script>

<style lang="scss">
@import '~styles/shared';

.team-member {
    min-height: 100%;

    &__container {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }

    // &__avatar {
    //     background-position: center;
    //     background-size: cover;
    //     @include border-radius(50%);
    //     // margin-bottom: $spacer * 1.5;
    //     transition: $transition-base;
    //     cursor: pointer;
    //     transform-origin: center;
    //
    //     &::before {
    //         content: '';
    //         opacity: 0;
    //         position: absolute;
    //         top: 50%;
    //         left: 50%;
    //         transform: translate(-50%, -50%);
    //         @include border-radius(50%);
    //         width: 0;
    //         height: 0;
    //         background-color: rgba($white, .4);
    //         z-index: 1;
    //         transform-origin: center;
    //         transition: $transition-base;
    //     }
    //
    //     &::after {
    //         content: '+';
    //         opacity: 0;
    //         position: absolute;
    //         top: 50%;
    //         left: 50%;
    //         transform: translate(-50%, -50%);
    //         @include border-radius(50%);
    //         width: 0;
    //         height: 0;
    //         color: $white;
    //         z-index: 2;
    //         display: flex;
    //         justify-content: center;
    //         align-items: center;
    //         font-size: 200%;
    //         background-color: rgba($primary, .5);
    //         transform-origin: center;
    //         transition: $transition-base;
    //     }
    //
    //     &:hover {
    //         position: relative;
    //         transition: $transition-base;
    //         transform-origin: center;
    //
    //         &::before {
    //             opacity: 1;
    //             top: 50%;
    //             left: 50%;
    //             transform: translate(-50%, -50%);
    //             width: 100%;
    //             height: 100%;
    //             transition: $transition-base;
    //             transform-origin: center;
    //         }
    //
    //         &::after {
    //             top: 50%;
    //             left: 50%;
    //             transform: translate(-50%, -50%);
    //             opacity: 1;
    //             width: 50%;
    //             height: 50%;
    //             transition: $transition-base;
    //             transform-origin: center;
    //         }
    //     }
    // }

    &__info {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }
}

.team-member-avatar {
    flex: 1 1 50%;
    min-height: 50%;

    &__avatar {
        width: 100%;
        height: 0;
        padding-bottom: 100%;
        position: relative;
        overflow: hidden;
        @include border-radius(50%);
        cursor: pointer;
    }

    &__img {
        position: absolute;
        min-width: 100%;
        min-height: 100%;
        width: 100%;
        height: auto;
    }

    &__overlay {
        position: absolute;
        height: 100%;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba($primary, .6);
        z-index: 1;
        opacity: 0;
        transform-origin: center;
        transition: $transition-base;
    }

    &__overlay-text {
        color: $white;
        font-size: $h3-font-size;
        font-weight: $font-weight-bold;
    }

    &:hover &__overlay {
        opacity: 1;
        transform-origin: center;
        transition: $transition-base;
    }
}

.team-member-info {
    flex: 1 1 50%;
    min-height: 50%;

    &__name {
        padding-top: $spacer / 2;
        margin-bottom: $spacer / 2;
        text-transform: uppercase;
        text-align: center;
        position: relative;
        border-bottom: 4px solid $primary;
        line-height: 2;
    }

    &__role {
        padding-top: $spacer / 2;
        font-size: $font-size-sm;
        text-align: center;
    }

    &__social {
        padding-top: $spacer / 2;
        display: flex;
        justify-content: center;
        margin-top: auto;

        div {
            padding: $spacer / 2;
        }
    }
}
</style>
