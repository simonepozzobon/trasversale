<template lang="html">
    <div class="ui-sharing">
        <ui-link
            class="ui-sharing__link"
            target="_blank"
            :is-simple="true"
            :url="facebookUrl">
            <facebook
                width="36px"
                height="36px"
                :hoverable="true"
                hover-color="primary"/>
        </ui-link>
        <ui-link
            class="ui-sharing__link"
            target="_blank"
            :is-simple="true"
            :url="twitterUrl">
            <twitter
                width="36px"
                height="36px"
                :hoverable="true"
                hover-color="primary"/>
        </ui-link>
        <ui-link
            class="ui-sharing__link"
            target="_blank"
            :is-simple="true"
            :url="linkedinUrl">
            <linkedin
                width="36px"
                height="36px"
                :hoverable="true"
                hover-color="primary"/>
        </ui-link>
        <ui-link
            class="ui-sharing__link"
            target="_blank"
            :is-simple="true"
            :url="mailUrl">
            <mail-dark
                width="36px"
                height="36px"
                :hoverable="true"
                hover-color="primary"/>
        </ui-link>
    </div>
</template>

<script>
import UiLink from './UiLink.vue'
import { Facebook, Linkedin, MailDark, Twitter } from '../icons'

export default {
    name: 'UiSharing',
    components: {
        Facebook,
        Linkedin,
        MailDark,
        UiLink,
        Twitter,
    },
    data: function() {
        return {

        }
    },
    computed: {
        currentUrl: function() {
            let url = window.location.href
            let encoded = encodeURIComponent(url);
            return encoded
        },
        facebookUrl: function() {
            return 'https://www.facebook.com/sharer/sharer.php?u='+this.currentUrl
        },
        twitterUrl: function() {
            return 'https://twitter.com/home?status=5xmille%20Ospedale%20San%20Raffaele.%20Insieme%20possiamo%20fare%20tanto%20e%20con%20il%20tuo%205xmille%20possiamo%20fare%20di%20pi%C3%B9.%20Firma%20per%20la%20ricerca%20sanitaria.%20'+this.currentUrl
        },
        linkedinUrl: function() {
            return 'https://www.linkedin.com/shareArticle?mini=true&url='+this.currentUrl+'&title=5xmille%20Ospedale%20San%20Raffaele&summary=Insieme%20possiamo%20fare%20tanto%20e%20con%20il%20tuo%205xmille%20possiamo%20fare%20di%20pi%C3%B9.%20Firma%20per%20la%20ricerca%20sanitaria.%20Visita%20il%20sito%20http%3A//www.5xmille.org&source='
        },
        mailUrl: function() {
            return 'mailto:?&subject=5xmille all\'ospedale San Raffaele&body=Voglio%20condividere%20con%20te%20questa%20importante%20opportunit%C3%A0%20per%20sostenere%20la%20ricerca%20-%20' + this.currentUrl
        }
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.ui-sharing {
    margin-top: $block-spacer;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;

    &__link {
        padding-left: $spacer;
        padding-right: $spacer;
    }
}
</style>
