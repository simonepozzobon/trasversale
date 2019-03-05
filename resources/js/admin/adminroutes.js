const routes = [
    {
        path: '/',
        name: 'home',
        component: require('./views/Home.vue').default
    },
    {
        path: '/sub/:id',
        name: 'sub',
        component: require('./views/SubPage.vue').default
    }
]

export default routes
