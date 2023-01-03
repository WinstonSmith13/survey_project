import { createRouter, createWebHistory } from "vue-router";
import Dashboard from "../views/Dashboard.vue";
import Login from "../views/Login.vue";
import Register from "../views/Register.vue";
import DefaultLayout from "../components/DefaultLayout.vue";
import Surveys from "../views/Surveys.vue";
import SurveyView from "../views/SurveyView.vue";
import store from "../store";
import AuthLayout from "../components/AuthLayout.vue";
import SurveyPublicView from "../views/SurveyPublicView.vue"

const routes = [
  //Creation d'une nouvelle route pour acceder aux vues des formulaires publics. 
  //Avec l'utilisation de la variable slug qui permet d'avoir chaque formulaires. 
  {
    //definition de la route 
path:'/view/survey/:slug',
//definition du nom de la route 
name: 'SurveyPublicView',
component: SurveyPublicView
  },
  {
    path: '/',
    redirect: '/dashboard',
    component: DefaultLayout,
    meta: { requiresAuth: true },
    children: [
      { path: '/dashboard', name: 'Dashboard', component: Dashboard },
      { path: '/surveys', name: 'Surveys', component: Surveys },
      { path: '/surveys/create', name: 'SurveyCreate', component: SurveyView },
      { path: '/surveys/:id', name: 'SurveyView', component: SurveyView },
    ]
  },
  {
    path: '/auth',
    name: 'Auth',
    component: AuthLayout,
    //factorisation du code pour la route
    meta: { isGuest: true },
    children: [
      {
        path: '/login',
        name: 'Login',
        component: Login
      },
      {
        path: '/register',
        name: 'Register',
        component: Register
      },

    ]
  },

];

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !store.state.user.token) {
    // Vérifie si l'utilisateur n'est pas authentifié
    next({
      path: '/login'
    })
  }
  //si token exite et si l'utilisateur accède à la page de Login ou bien de Register alors on le redirige vers la page Accueil
  else if (store.state.user.token && (to.meta.isGuest)) {
    next({ name: 'Dashboard' });
  }
  else {
    next()
  }
})

export default router
