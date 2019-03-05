<template lang="html">
    <div class="team-member"
        :class="'col-md-'+gridCol">
        <div ref="member" class="team-member__sizer"></div>
        <div ref="avatar" class="team-member__avatar" @click="openMember"></div>
        <div class="team-member__name">
            {{ member.name }}
        </div>
        <div class="team-member__role">
            {{ member.role }}
        </div>
        <div class="team-member__social">
            <div v-for="(social, i, key) in this.socials">
                <a :href="social.value" v-if="social.name == 'twitter'">T</a>
                <a :href="social.value" v-else>Ln</a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'UiTeamSingle',
    props: {
        gridCol: {
            type: Number,
            default: 0,
        },
        member: {
            type: Object,
            default: function() {}
        }
    },
    computed: {
        socials: function() {
            let newlist = []
            let social = this.member.social

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
            return newlist
        }
    },
    methods: {
        setAvatarWidth: function() {
            let container = this.$refs.member
            let width = container.getBoundingClientRect().width
            let avatar = this.$refs.avatar
            avatar.style.width = width + 'px'
            avatar.style.height = width + 'px'
            avatar.style.backgroundImage = 'url('+ this.member.img +')'
        },
        openMember: function() {
            this.$emit('open-member', this.member)
        }
    },
    mounted: function() {
        this.setAvatarWidth()
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.team-member {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    &__sizer {
        width: 80%;
    }

    &__avatar {
        background-position: center;
        background-size: cover;
        @include border-radius(50%);
        margin-bottom: $spacer * 1.5;
        transition: $transition-base;
        cursor: pointer;
        transform-origin: center;

        &::before {
            content: '';
            opacity: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            @include border-radius(50%);
            width: 0;
            height: 0;
            background-color: rgba($white, .4);
            z-index: 1;
            transform-origin: center;
            transition: $transition-base;
        }

        &::after {
            content: '+';
            opacity: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            @include border-radius(50%);
            width: 0;
            height: 0;
            color: $white;
            z-index: 2;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 200%;
            background-color: rgba($primary, .5);
            transform-origin: center;
            transition: $transition-base;
        }

        &:hover {
            position: relative;
            transition: $transition-base;
            transform-origin: center;

            &::before {
                opacity: 1;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 100%;
                height: 100%;
                transition: $transition-base;
                transform-origin: center;
            }

            &::after {
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                opacity: 1;
                width: 50%;
                height: 50%;
                transition: $transition-base;
                transform-origin: center;
            }
        }
    }

    &__name {
        display: inline-block;
        text-transform: uppercase;
        position: relative;
        border-bottom: 4px solid $primary;
        line-height: 2;
        margin-bottom: $spacer / 2;
    }

    &__role {
        font-size: $font-size-sm;
    }

    &__social {
        display: flex;
        justify-content: center;

        div {
            padding: $spacer / 2;
        }
    }
}
</style>
