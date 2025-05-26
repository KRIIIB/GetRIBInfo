<template>
    <Nav title="| GetRibInfo with Vue / Laravel"></Nav>
    <div class="card p-5">
        <router-view>
            <div class="card-body">
                <h4><router-link to="/operations">Opérations</router-link></h4>
                <p> 
                    Pour un RIB donné et selon un encadrement de date (date minimum, date maximum), de retourner la liste des opérations répondant à ces critères.
                </p>
            </div>
            <hr>
            <div class="card-body">
                <h4><router-link to="/solde" class="inline">Solde</router-link></h4>
                <p>
                    Pour un RIB donné et selon un encadrement de date, de calculer le solde du compte
                    sur la période (total des recettes - total des dépenses)
                </p>
            </div>
        </router-view>
    </div>
</template>

<script>
    import Nav from './Nav.vue';
    import Operations from './Operations.vue';
    import Solde from './Solde.vue';

    export default {
        components: { Nav, Operations, Solde, },
        mounted() {
            console.log('Component mounted');
        },
        data: function() {
            return {
                show: ''
            }
        },
    }
</script>