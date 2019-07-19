
const loginPage = require('./components/front/login.vue').default;
const signUpPage = require('./components/front/signup.vue').default;
const dashboardPage = require('./components/back/home.vue').default;
const mouldPage = require('./components/back/mould.vue').default;
const ringCategory = require('./components/back/ringCategory.vue').default;
const clientPage = require('./components/back/client.vue').default;
const superAdminPage = require('./components/back/superadmin.vue').default;


export default{
    mode:'history',
    routes: [
        { path: '/login', name:'login', component: loginPage, 
            meta: { 
                title: "Login Page"
            } 
        },
        { path: '/add-user', name:'signup', component: signUpPage, 
            meta: { 
                title: "Sign Up"
            } 
        },
        { path: '/superadmin', name:'superadmin', component: superAdminPage, 
            meta: { 
                title: "Sign Up"
            } 
        },
        { path: '/dashboard', name:'dashboard', component: dashboardPage, 
            meta: { 
                title: "Dashboard"
            }
        },
        { path: '/mould', name:'mould', component: mouldPage, 
            meta: { 
                title: "Mould"
            }
        },
        { path: '/ring-category', name:'ring-category', component: ringCategory, 
            meta: { 
                title: "Ring Category"
            }
        },
        { path: '/client', name:'client', component: clientPage, 
            meta: { 
                title: "Client"
            }
        },
        { path: "*", component: loginPage }
    ]
}
