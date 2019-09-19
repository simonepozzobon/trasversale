import HomeContainer from './views/HomeContainer.vue'
import Home from './views/Home.vue'
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
        },
        {
            path: 'posts/:type/create',
            name: 'post-create',
            component: PostCreate
        },
        {
            path: 'posts/:type/:id',
            name: 'post-edit',
            component: PostEdit
        },
        {
            path: 'posts/:type',
            name: 'posts',
            component: PostsCrud,
        },
        {
            path: 'page/:page',
            component: PageContainer,
            children: [{
                    path: '',
                    name: 'page',
                    component: Page,
                },
                {
                    path: ':sub',
                    component: SubPageContainer,
                    children: [{
                        path: '',
                        name: 'sub',
                        component: SubPage,
                    }]
                },

            ],
        },
        {
            path: 'subscribers/:id',
            name: 'manage-subscribers',
            component: ManageSubscribers,
        }
    ],
}]

export default routes
