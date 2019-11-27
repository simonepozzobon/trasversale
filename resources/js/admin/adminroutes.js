import CreateSubscriber from './views/CreateSubscriber.vue'
import HomeContainer from './views/HomeContainer.vue'
import Home from './views/Home.vue'
import Login from './views/Login.vue'
import ManageSubscribers from './views/ManageSubscribers.vue'
import PageContainer from './views/PageContainer.vue'
import Page from './views/Page.vue'
import PostsContainer from './views/PostsContainer.vue'
import PostCreate from './views/PostCreate.vue'
import PostEdit from './views/PostEdit.vue'
import PostsCrud from './views/PostsCrud.vue'
import SubPageContainer from './views/SubPageContainer.vue'
import SubPage from './views/SubPage.vue'

const routes = [{
    path: '/',
    component: HomeContainer,
    children: [{
            path: '',
            name: 'home',
            component: Home,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: 'login',
            name: 'login',
            component: Login,
        },
        {
            path: 'posts/:type/create',
            name: 'post-create',
            component: PostCreate,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: 'posts/:type/:id',
            name: 'post-edit',
            component: PostEdit,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: 'posts/:type',
            name: 'posts',
            component: PostsCrud,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: 'page/:page',
            component: PageContainer,
            children: [{
                    path: '',
                    name: 'page',
                    component: Page,
                    meta: {
                        requiresAuth: true
                    }
                },
                {
                    path: ':sub',
                    component: SubPageContainer,
                    children: [{
                        path: '',
                        name: 'sub',
                        component: SubPage,
                        meta: {
                            requiresAuth: true
                        }
                    }]
                },

            ],
        },
        {
            path: 'subscribers/:id',
            name: 'manage-subscribers',
            component: ManageSubscribers,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: 'create-subscriber/:product_id',
            name: 'create-subscriber',
            component: CreateSubscriber,
            meta: {
                requiresAuth: true
            }
        },
    ],
}]

export default routes
