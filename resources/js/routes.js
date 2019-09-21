import Home from './views/Home.vue'
import HomeContainer from './views/HomeContainer.vue'
import PageContainer from './views/PageContainer.vue'
import SubPageContainer from './views/SubPageContainer.vue'
import Product from './views/Product.vue'
import Page from './views/Page.vue'
import SearchResults from './views/SearchResults.vue'
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
            path: 'risultati-ricerca',
            name: 'search',
            component: SearchResults,
        },
        {
            path: ':page',
            component: PageContainer,
            children: [{
                    path: '',
                    name: 'page',
                    component: Page,
                },
                {
                    path: ':subpage',
                    component: SubPageContainer,
                    children: [{
                            path: '',
                            name: 'subpage',
                            component: SubPage,
                        },
                        {
                            path: ':item',
                            name: 'item',
                            component: Product,
                        },
                    ]
                },
            ]
        }
    ]
}, ]

export default routes
