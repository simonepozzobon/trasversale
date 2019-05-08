import Home from './views/Home.vue'

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home,
    },
    {
        path: '/:page',
        name: 'page',
        component: Home,
        children: [
            {
                path: ':subpage',
                name: 'subpage',
                component: Home,
            }
        ]
    }
]

export default routes
