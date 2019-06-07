
import HomeContainer from './views/HomeContainer.vue'
import Home from './views/Home.vue'
import PageContainer from './views/PageContainer.vue'
import Page from './views/Page.vue'
import PostsContainer from './views/PostsContainer.vue'
import PostCreate from './views/PostCreate.vue'
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
                path: 'posts/:type',
                component: PostsContainer,
                children: [
                    {
                        path: '',
                        name: 'posts',
                        component: PostsCrud,
                    },
                    {
                        path: 'create',
                        name: 'post-create',
                        component: PostCreate
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
