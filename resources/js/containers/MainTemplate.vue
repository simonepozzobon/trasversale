<template lang="html">
    <div class="container">
        <main-menu :pages="parsedPages"/>
        <div>
            <div class="row custom-blue main-content">
                <div class="col-md-9 custom-green main-content__content">
                    <router-view />
                </div>
                <div class="col-md-3 custom-red main-content__sidebar">
                    <ui-sidebar-image
                        src="/dummies/il-team/img-1.png"
                        alt="titolo"/>
                    <p>
                        Apparently we had reached a great height in the atmosphere, for the sky was a dead black, and the stars had ceased to twinkle. By the same illusion which lifts the horizon of the sea to the level of the spectator on a hillside, the sable cloud beneath was dished out, and the car seemed to float in the middle of an immense dark sphere, whose upper half was strewn with silver.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import MainMenu from './MainMenu.vue'
import { UiSidebarImage } from '../ui'

export default {
    name: 'MainTemplate',
    components: {
        MainMenu,
        UiSidebarImage,
    },
    props: {
        pages: {
            type: String,
            default: function() {}
        }
    },
    computed: {
        parsedPages: function() {
            if (this.pages) {
                return JSON.parse(this.pages)
            }
        }
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
$debug-opacity: 0 !default;

.main-content {
    &__content {
        padding-top: $spacer;
    }

    &__sidebar {
        padding-top: $spacer;
    }
}

.custom-blue {
    background-color: rgba($blue, $debug-opacity);
}

.custom-green {
    background-color: rgba($teal, $debug-opacity);
}

.custom-red {
    background-color: rgba($red, $debug-opacity);
}
</style>
