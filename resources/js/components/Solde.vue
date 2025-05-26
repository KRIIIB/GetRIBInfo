<template>
    <Nav title=" | Solde"></Nav>
    <div class="card p-5">
        
        <!-- Show list of existing rib -->
        <div class="form-group" v-if="!showResult">
            <div class="form group">
                <select class="form-control" v-model="selected" required>
                    <option value="">Séléctionner un rib</option>
                    <option v-for="client in ribs" :key="client.id" :value="client.id">
                        {{ client.rib }}
                    </option>
                </select>                 
            </div>
            <div class="form group">
                <label for="date_begin">Date minimal:</label>
                <input class="form-control" type="date" name="date_begin" id="date_begin" v-model="date_begin">
            </div>
            <div  class="form group">
                <label for="date_end">Date max:</label>
                <input class="form-control" type="date" name="date_end" id="date_end" v-model="date_end">
            </div>
            <div class="p-2 justify-content-center align-content-center">
                <div class="">
                    <button @click="searchOperations()" class="btn btn-primary">Calculer</button>
                </div>
            </div>
        </div>
        <!-- Show operations for the selected rib-->
        <div class="list-group p-1" v-if="showResult">
            <caption class="h4">Solde du {{ date_begin }} au  {{ date_end }} pour le rib:{{ selected }}</caption>
            <table class="table" >
                <thead>
                    <tr align="center">
                        <th scope="col">Rib</th>
                        <th scope="col">Récette</th>
                        <th scope="col">Dépense</th>
                        <th scope="col">Valeur</th>
                    </tr>
                </thead>
                <tbody>
                    <tr scope="row" align="center">
                        <td>{{ selected }}</td>
                        <td>{{ totalRecette }}</td>
                        <td>{{ totalDepense }}</td>
                        <td>{{ totalOperations }}</td>
                    </tr>
                    <tr align="center">
                        <td colspan="3"><button @click="back()" class="btn btn-primary">Rechercher</button></td>

                    </tr>
                </tbody>
            </table>
            <div class="row">
                <!--  -->
            </div>
        </div>
    </div>
    <Modal @close="toggleModal" :modalShow="modalShow">

        <p>{{ error }}</p>

    </Modal>
</template>

<script>
    import Nav from './Nav.vue';
    import Modal from './Modal.vue';


    export default {
        components: { Nav , Modal},

        data: function() {
            return {
                modalShow : false,
                showResult: false,
                operations: [],
                totalRecette: 0,
                totalDepense: 0,
                totalOperations: 0,
                ribs: [],
                selected: '',
                date_begin: new Date().toISOString().substr(0, 10),
                date_end: new Date().toISOString().substr(0, 10),
                error :"",
            }
        },
        mounted() {
            console.log('Component mounted');
            this.loadRibs();
        },
        methods: {
            toggleModal() {
                this.modalShow = !this.modalShow;
            },

            loadRibs: function() {
                /*
                    send a GET request to laravel api to obtain all available "ribs"
                    Laravel API "/api/operations/" will make the request to the external api
                */
                axios.get("http://127.0.0.1:8000/api/ribs")
                .then((result) => {
                    result.data.forEach(client => {                
                        if (!this.ribs.some(c => c.id === client.id)) {
                            this.ribs.push(client);
                        }
                    });
                }).catch((err) => {
                   this.error=err;
                   this.toggleModal();
                });

            },
            searchOperations: function() {
                /*
                    send a GET request to laravel api to obtain all available operations filter by "rib", and "date"
                    Laravel API "/api/rib/" will make the request to the external api, and add the additional field(Récéttes, et Dépenses)
                */
                if (this.selected === "") {
                    alert("Vous devez choisir un rib!");
                } else {
                    const url = `http://127.0.0.1:8000/api/operations/rib/${this.selected}`;
                    axios.get(url, { 
                        params: {
                            begin: this.date_begin,
                            end: this.date_end
                        }
                    })
                    .then((result) => {
                        
                        this.operations = Array.isArray(result.data) ? result.data : [];

                        this.operations.sort((a, b) => new Date(a.date) - new Date(b.date));

                        this.operations = this.operations.map(op => ({
                            ...op,
                            recette: op.amount > 0 ? op.amount : 0,
                            depense: op.amount < 0 ? -op.amount : 0
                        }));

                        this.solde();

                        if (this.operations.length === 0) {
                            alert('Aucun résultat trouvé.');
                        } else {
                            this.showResult = true;
                        }
                    })
                    .catch((err) => {
                        this.error = err;
                        this.toggleModal();
                    });
                } 
            },
            back: function (amount) {
                /*
                    This function hide the results table and show the search ui
                 */
                this.showResult = !this.showResult;
                this.loadRibs();

            },
            solde: function() {
                /* 
                    This function calculate the total amount of Recete and Depenses, 
                    The it makes the fields visible on the ui.
                */

                if (this.soldeVisible) {
                    // reset totals for recette and depenses
                    this.soldeVisible = !this.soldeVisible;    
                    this.totalRecette = 0;
                    this.totalDepense = 0;
                }
                else {
                    // Sum total for recette
                    this.operations.forEach(operation => {
                        this.totalRecette += operation.recette;
                    });
                    
                    // Sum total for depense
                    this.operations.forEach(operation => {
                        this.totalDepense += operation.depense;
                    });
                    this.totalOperations = this.totalRecette - this.totalDepense;
                    
                    this.rounder();
                }
            },
            rounder: function() {
                this.totalRecette = Math.round(this.totalRecette * 100) / 100
                this.totalDepense = Math.round(this.totalDepense * 100) / 100
                this.totalOperations = Math.round(this.totalOperations * 100) / 100
            }

        }
    }
</script>