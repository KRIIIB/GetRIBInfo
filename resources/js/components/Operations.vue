<template>
    <Nav title=" | Opérations"></Nav>
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
                    <button @click="searchOperations()" class="btn btn-primary">Chercher opérations</button>
                </div>
            </div>
        </div>
        <!-- Show operations for the selected rib-->
        <div class="list-group p-1" v-if="showResult">
            <caption class="h4">Operation du {{ date_begin }} au  {{ date_end }} pour le rib:{{ selected }}</caption>
            <table class="table" >
                <thead>
                    <tr align="center">
                        <th scope="col">ID</th>
                        <th scope="col">Libellé</th>
                        <th scope="col">Date</th>
                        <th scope="col">Valeur</th>
                        <th scope="col">Récette</th>
                        <th scope="col">Dépense</th>
                    </tr>
                </thead>
                <tbody>
                    <tr scope="row" v-for="op in operations" :key="op" align="right">
                        <td>{{ op.id }}</td>
                        <td>{{ op.label }}</td>
                        <td>{{ op.date }}</td>
                        <td>{{ op.amount }}</td>
                        <td>{{ op.recette }}</td>
                        <td>{{ op.depense }}</td>
                        <td><button class="btn btn-primary" @click="editOperation(op)">Edit</button></td>
                        <td><button class="btn btn-primary delete" @click="deleteOperation(op.id)">Delete</button></td>
                    </tr>
                    <tr v-if="soldeVisible" align="right">
                        <td colspan="4" class="bg-light"></td>
                        <td class="text-white bg-success">{{ totalRecette }}</td>
                        <td class="bg-warning">{{ totalDepense }}</td>
                    </tr>
                    <tr align="center">
                        <td colspan="3"><button @click="back()" class="btn btn-primary">Rechercher</button></td> 
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-if="showResult" class="list-group p-2">
            <caption class="h4" v-if="edit">Editing : </caption>
            <caption class="h4" v-if="!edit">Add new Operation : </caption>
            <form @submit.prevent="addOperation" class="form-edit">
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="Label" v-model="operation.label">
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="rib_id" v-model="operation.rib_id">
                </div>
                <div class="form-group">
                    <input class="form-control" type="date" placeholder="date" v-model="operation.date">
                </div>
                <div class="form-group">
                    <input class="form-control" type="number" placeholder="amount" v-model="operation.amount">
                </div>
                <button class="btn btn-primary" type="submit">Save</button>
            </form>

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
                modalShow: false,
                showResult: false,
                edit: false,
                operations: [],
                totalRecette: 0,
                totalDepense: 0,
                totalOperations: 0,
                soldeVisible: false,
                ribs: [],
                selected: '',
                date_begin: new Date().toISOString().substr(0, 10),
                date_end: new Date().toISOString().substr(0, 10),
                error:"",
                operation:{
                    operation_id:'',
                    label:'',
                    rib_id:'',
                    date:'',
                    amount:'',
                }, 
                
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
            back : function (amount) {
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

                    // Réinitialiser les totaux
                    this.totalRecette = 0;
                    this.totalDepense = 0;

                    // Recalculer les totaux à partir des opérations
                    this.operations.forEach(operation => {
                        this.totalRecette += operation.recette;
                        this.totalDepense += operation.depense;
                    });

                    // Forcer l'affichage du solde
                    this.soldeVisible = true;

            },

            deleteOperation(id) {
                if(confirm('Etes-vous sûr ?')){
                    fetch(`api/operation/${id}`, {
                        method: 'delete'
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert('Operation Removed');
                        this.searchOperations();
                    })
                    .catch((err) => {
                        this.error = err;
                        this.toggleModal();
                    });
                }
            },

            addOperation(){
                if(this.edit === false) {
                    fetch('api/operation', {
                        method:'post',
                        body: JSON.stringify(this.operation),
                        headers:{
                            'content-type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.operation.label ='';
                        this.operation.rib_id ='';
                        this.operation.date ='';
                        this.operation.amount ='';
                        alert('Operation Added');
                        this.searchOperations();
                    })
                    .catch((err) => {
                        this.error = err;
                        this.toggleModal();
                    })
                } else {
                    fetch('api/operation', {
                        method:'put',
                        body: JSON.stringify(this.operation),
                        headers:{
                            'content-type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.operation.label ='';
                        this.operation.rib_id ='';
                        this.operation.date ='';
                        this.operation.amount ='';
                        this.operation.operation_id = '';
                        alert('Operation Updated');
                        this.searchOperations();
                        this.edit = false;
                    })
                    .catch((err) => {
                        this.error = err;
                        this.toggleModal();
                    })
                }

            },

            editOperation(operation){
                this.edit = true;
                this.operation.operation_id = operation.id;
                this.operation.label = operation.label;
                this.operation.rib_id = operation.rib_id;
                this.operation.date = operation.date;
                this.operation.amount = operation.amount;
            }

        }
    }
</script>