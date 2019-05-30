<template lang="html">
    <div class="main-footer">
        <div class="main-footer__container container">
            <div class="row">
                <div class="col-md-4 text-white">
                    <img src="/svg/logo.svg" alt="" style="width: 250px" class="main-footer__svg">
                </div>
                <div class="col-md-4 text-white">
                    <p class="pt-4">
                        Trasversale srl<br>
                        viale del Vignola 70, 00196 Roma<br>
                        tel. 06 3600 6259<br>
                        fax 06 36090201<br>
                        <br>
                        trasversale@trasversale.com<br>
                        trasversale@pec.trasversale.com<br>
                        <br>
                        CF.‎05721611001<br>
                        Partita IVA ‎‎IT05721611001<br>
                    </p>
                </div>
                <div class="col-md-2 text-white">
                    <p class="pt-4">
                        site map<br>
                        formazione<br>
                        educational<br>
                        corsi<br>
                    </p>
                </div>
                <div class="col-md-2 text-white">
                    <p class="pt-4">
                        carta della qualità<br>
                        accreditamenti<br>
                        privacy policy<br>
                        credits<br>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'MainFooter',
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.main-footer {
    background-color: $primary;
    height: 100%;

    &__container {
        padding-top: $spacer;
        min-height: 300px;
    }

    &__svg {
        filter: invert(1);
    }
}
</style>
