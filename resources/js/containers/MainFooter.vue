<template>
<div class="main-footer">
    <div class="main-footer__container container">
        <div class="row">
            <div class="col-md-4 text-white">
                <img
                    src="/svg/logo.svg"
                    alt=""
                    style="width: 250px"
                    class="main-footer__svg"
                >
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
        <div class="row py-5">
            <div class="col d-flex justify-content-center">
                <button
                    class="btn btn-link text-white"
                    @click="showPrivacy"
                >
                    Privacy
                </button>
            </div>
            <b-modal
                ref="privacy"
                title="Privacy Policy"
                size="lg"
            >
                <p class="my-4">
                    Il Regolamento UE 2016/679 (di seguito GDPR) prevede e rafforza la protezione e il trattamento dei dati personali alla luce dei principi di correttezza, liceità, trasparenza, tutela della riservatezza e dei diritti
                    dell’interessato
                    in merito ai propri dati.  I dati personali saranno sottoposti a modalità di trattamento sia cartaceo sia elettronico e/o automatizzato, quindi con modalità sia manuali che informatiche. In ogni caso saranno adottate tutte le
                    procedure idonee a proteggerne la riservatezza, nel rispetto delle norme vigenti e del segreto professionale.  Saranno utilizzate adeguate misure di sicurezza al fine di garantire la protezione, la sicurezza, l’integrità e
                    l’accessibilità dei dati personali.  I dati personali saranno conservati solo per il tempo necessario al conseguimento delle finalità per le quali sono stati raccolti o per qualsiasi altra legittima finalità a essi collegata. I
                    dati
                    personali che non siano più necessari, o per i quali non vi sia più un presupposto giuridico per la relativa conservazione, verranno anonimizzati irreversibilmente o distrutti in modo sicuro.
                </p>
                <template slot="modal-footer">
                    <button
                        class="btn btn-primary"
                        @click="hidePrivacy"
                    >
                        Chiudi
                    </button>
                </template>
            </b-modal>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: 'MainFooter',
    methods: {
        showPrivacy: function () {
            this.$refs.privacy.show()
        },
        hidePrivacy: function () {
            this.$refs.privacy.hide()
        }
    },
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
