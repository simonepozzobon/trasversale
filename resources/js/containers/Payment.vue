<template>
<div class="payment">
    <div class="payment__title">
        <ui-title
            title="Pagamento"
            :is-column="true"
            tag="h4"
            font-size="h4"
            color="white"
        />
    </div>
    <div class="payment__dropin">
        <dropin
            wrapperClass="constrain"
            :authToken="authToken"
            :collectCardHolderName="true"
            :enableDataCollector="true"
            :enablePayPal="true"
        >
        </dropin>
    </div>
    <div class="payment__action">
        <ui-button
            title="Procedi al pagamento"
            color="yellow"
            :is-active="true"
            :has-container="false"
            :has-margin="false"
            display="inline-block"
        />
    </div>
</div>
</template>

<script>
import Dropin from './Dropin.vue'

import {
    UiBlock,
    UiButton,
    UiRow,
    UiTitle,
}
from '../ui'

export default {
    name: 'Payment',
    components: {
        Dropin,
        UiBlock,
        UiButton,
        UiRow,
        UiTitle,
    },
    data: function () {
        return {
            authToken: null,
        }
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
