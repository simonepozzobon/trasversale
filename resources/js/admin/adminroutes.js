import Corsi from './views/Corsi.vue'
import Notizie from './views/Notizie.vue'

import HomeContainer from './views/HomeContainer.vue'
import Home from './views/Home.vue'
import PageContainer from './views/PageContainer.vue'
import Page from './views/Page.vue'
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
                path: 'corsi',
                name: 'corsi',
                component: Corsi,
            },
            {
                path: 'notizie',
                name: 'notizie',
                component: Notizie,
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
