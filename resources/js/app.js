require('./bootstrap');

import { createApp } from 'vue';
import router from './router';
import Home from './components/Home.vue';


// const app = createApp ({});
// app.component('home', Home);
// app.mount('#app');

createApp({
    components: {
        Home
    },
})
.use(router)
.mount('#app')