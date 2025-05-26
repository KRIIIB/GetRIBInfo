import { createRouter, createWebHistory } from "vue-router";
import Home from '../components/Home.vue';
import Operations from '../components/Operations.vue';
import Solde from '../components/Solde.vue';

const routes = [
    {
        path: '/accueil',
        name: "accueil",
        component: Home,
    },
    {
        path: '/operations',
        name: "operations",
        component: Operations,
    },
    {
        path: '/solde',
        name: "solde",
        component: Solde,
    },
    {
        path: '/',
        name: "accueil",
        component: Home,
    },

];

export default createRouter ({
    history: createWebHistory(),
    routes
});