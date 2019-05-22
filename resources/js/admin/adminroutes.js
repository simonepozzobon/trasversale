const routes = [
    {
        path: '/',
        component: require('./views/HomeContainer.vue').default,
        children: [
            {
                path: '',
                name: 'home',
                component: require('./views/Home.vue').default,
            },
            {
                path: ':page',
                component: require('./views/PageContainer.vue').default,
                children: [
                    {
                        path: '',
                        name: 'page',
                        component: require('./views/Page.vue').default,
                    },
                    {
                        path: ':sub',
                        component: require('./views/SubPageContainer.vue').default,
                        children: [
                            {
                                path: '',
                                name: 'sub',
                                component: require('./views/SubPage.vue').default,
                            }
                        ]
                    }
                ],
            }
        ],
    }
]

export default routes
