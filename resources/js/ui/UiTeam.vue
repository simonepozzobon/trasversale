<template lang="html">
    <div class="team">
        <div class="row">
            <!-- <div class="team__overlay" ref="overlay"></div> -->
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
    </div>
</template>

<script>
import UiTeamDetail from './UiTeamDetail.vue'
import UiTeamSingle from './UiTeamSingle.vue'
import {
    gsap
}
from 'gsap'

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
            default: function () {}
        }
    },
    data: function () {
        return {
            overlay: null,
            selected: {
                name: '',
                role: '',
                description: '',
            },
        }
    },
    watch: {},
    methods: {
        openMember: function (member) {
            // console.log(member);
            this.selected = member
            this.$nextTick(() => {
                this.$refs.panel.getHeight(true, true)
            })
        },
        closeMember: function () {

        }
    },
    mounted: function () {
        // this.$nextTick(() => {
        //     this.openMember(this.people[0])
        // })
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.team {
    padding-top: $spacer * 1.618;
    position: relative;
}
</style>
