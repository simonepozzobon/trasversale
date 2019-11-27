<template>
<page-template title="Login">
    <div class="form-group">
        <label for="email">
            Email
        </label>
        <input
            type="email"
            class="form-control"
            name="email"
            v-model="username"
        >
    </div>
    <div class="form-group">
        <label for="password">
            Password
        </label>
        <input
            type="password"
            class="form-control"
            name="password"
            v-model="password"
        >
    </div>
    <div class="mt-5">
        <ui-button
            title="Login"
            color="primary"
            @click="login"
        />
    </div>
</page-template>
</template>

<script>
import PageTemplate from '../containers/PageTemplate.vue'
import {
    UiButton
}
from '../../ui'

export default {
    name: 'Login',
    components: {
        PageTemplate,
        UiButton,
    },
    data: function () {
        return {
            username: null,
            password: null,
        }
    },
    methods: {
        login: function () {
            let data = new FormData()
            data.append('email', this.username)
            data.append('password', this.password)

            this.$http.post('/api/login', data).then(response => {
                console.log('attempt login', response);
                if (response.data.success) {
                    this.$root.user = response.data.user
                    this.$root.token = response.data.token
                    this.$root.login(true)
                }
                else {
                    // console.log('error login');
                }
            })
        },
    },
    created: function () {
        this.$root.hasSidebar = false
    },
    beforeDestroy: function () {
        this.$root.hasSidebar = true
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
