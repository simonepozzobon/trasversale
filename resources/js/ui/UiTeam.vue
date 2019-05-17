<template lang="html">
    <div class="row team">
        <div class="team__overlay" ref="overlay"></div>
        <ui-team-single
            v-for="member in people"
            :key="member.id"
            :grid-col="gridCol"
            :member="member"
            @open-member="openMember"/>

        <ui-team-detail
            ref="panel"
            :member="this.selected"/>
    </div>
</template>

<script>
import UiTeamDetail from './UiTeamDetail.vue'
import UiTeamSingle from './UiTeamSingle.vue'
import { TweenMax } from 'gsap'

export default {
    name: 'UiTeam',
    components: {
        UiTeamDetail,
        UiTeamSingle,
    },
    props: {
        gridCol: {
            type: Number,
            default: 0,
        },
        people: {
            type: Array,
            default: function() {}
        }
    },
    data: function() {
        return {
            overlay: null,
            selected: {
                name: '',
                role: '',
                description: '',
            },
        }
    },
    methods: {
        openMember: function(member) {
            this.selected = member
            this.$nextTick(() => {
                this.$refs.panel.show()
            })
        },
        closeMember: function() {

        }
    },
    mounted: function() {
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.team {
    position: relative;
    margin-top: $spacer * 1.618;

    &__overlay {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba($white, .5);
        z-index: 1;
        display: none;
    }
}
</style>
