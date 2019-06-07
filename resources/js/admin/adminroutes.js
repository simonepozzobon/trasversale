
import HomeContainer from './views/HomeContainer.vue'
import Home from './views/Home.vue'
import PageContainer from './views/PageContainer.vue'
import Page from './views/Page.vue'
import PostsContainer from './views/PostsContainer.vue'
import PostsCrud from './views/PostsCrud.vue'
import SubPageContainer from './views/SubPageContainer.vue'
import SubPage from './views/SubPage.vue'

const routes = [
    {
        path: '/',
        component: HomeContainer,
        children: [
            {
                path: '',
                name: 'home',
                component: Home,
            },
            {
                path: 'posts',
                component: PostsContainer,
                children: [
                    {
                        path: ':type',
                        name: 'posts',
                        component: PostsCrud,
                    }
                ]
            },
            {
                path: ':page',
                component: PageContainer,
                children: [
                    {
                        path: '',
                        name: 'page',
                        component: Page,
                    },
                    {
                        path: ':sub',
                        component: SubPageContainer,
                        children: [
                            {
                                path: '',
                                name: 'sub',
                                component: SubPage,
                            }
                        ]
                    },

                ],
            }
        ],
    }
]

export default routes
