<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <router-link to="/">Accueil</router-link>
                    {{ title }}
                </a>
            <div class="navbar-nav">
                <a class="nav-link" href="#">
                    <router-link to="/operations">Operations</router-link>
                </a>
                <a class="nav-link">
                    <router-link to="/solde">Solde</router-link>
                </a>
            </div>
        </div>
    </nav>
</template>
<script>
    export default {
        props: {
            title: String,
        },
    };
        
</script>