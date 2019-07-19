<template>
    <div class="client">
        <div v-if="$store.state.user !== ''">
            <h3>{{ $route.meta.title }}</h3>
            <hr>
            <div class="main-action text-right">
                <v-card-title>
                    <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                    <button v-if="$store.state.user.roles !== 'officer'" class="btn btn-primary" v-b-modal.modal-center @click="modalTitle='New Client'">ADD CLIENT</button>
                </v-card-title>
            </div>

            

            <!-- Table Form :: for client -->
            <v-data-table :headers="headers" :items="clientData" :search="search" disable-initial-sort>
                <template v-slot:items="props">
                    <td>{{ props.item.client_name }}</td>
                    <td>{{ props.item.client_type }}</td>
                    <td>
                        <i aria-hidden="true" class="v-icon material-icons text-secondary" @click="viewRecord(props.item)">visibility</i>
                        <i v-if="$store.state.user.roles !== 'officer'" aria-hidden="true" class="v-icon material-icons text-primary" @click="updateClient(props.item)">create</i>
                        <i v-if="$store.state.user.roles !== 'officer'" aria-hidden="true" class="v-icon material-icons text-danger" v-b-modal.modal-confirm-delete @click="deleteData.deletedID=props.item.id; indexData=clientData.findIndex(x => x.id === props.item.id)">delete</i>
                    </td>
                </template>
                <template v-slot:no-results>
                    <v-alert :value="true" color="error" icon="warning">
                    Your search for "{{ search }}" found no results.
                    </v-alert>
                </template>
            </v-data-table>




            <!-- Modal Form :: for ring client -->
            <b-modal id="modal-center" centered ref="modal" :title="modalTitle" no-close-on-backdrop hide-header-close>
                <form ref="form">


                    <div class="form-group">
                        <label for="client-name">Name</label>
                        <input type="text" name="client-name" id="client_name" class="form-control" v-model="clientDetails.client_name"/>
                        <span class="help-block" v-if="errorsName">{{ clientNameError }}</span>
                    </div>

                    <div class="form-group">
                        <label for="client-type">Type</label>
                        <b-form-select v-model="clientDetails.client_type" :options="options"></b-form-select>
                        <span class="help-block" v-if="errorsType">{{ clientTypeError }}</span>
                    </div>

                </form>
                <template slot="modal-footer" slot-scope="">
                    <button class="btn btn-primary" @click="saveClient">SAVE</button>
                    <button class="btn btn-secondary" @click="closeClient">CANCEL</button>
                </template>
            </b-modal>


            <!-- Modal :: for Deleting Data -->
            <b-modal id="modal-confirm-delete" centered ref="modal" title="Delete Client">
                Are you sure you want to delete this data?
                
                <template slot="modal-footer" slot-scope="">
                    <button class="btn btn-primary" @click="deleteClient">Yes</button>
                    <button class="btn btn-secondary" @click="hideConfirmation">No</button>
                </template>
            </b-modal>

            
            <!-- Vlient :: for View Data -->
            <b-modal id="view-client" centered ref="modal" title="View Data">
                <div style="padding:10px;"><b>Name: </b>{{ viewData.name }}</div>
                <div style="padding:10px;"><b>Type: </b>{{ viewData.type }}</div>
            </b-modal>
        </div>
        <div v-if="$store.state.user === ''">
            <router-link to="/">Back to Login</router-link>
        </div>
    </div>
</template>





<script>
export default {
    data() {
        return {
            search: '',
            modalTitle: '',
            clientNameError: '',
            clientTypeError: '',
            errorsName: false,
            errorsType: false,
            headers: [
                { text: 'Name', value: 'client_name' },
                { text: 'Type', value: 'client_type' },
                { text: 'Action', value: 'action' }
            ],
            clientData: [],
            clientDetails: {
                client_name: '',
                client_type: ''
            },
            deleteData: {
                deletedID: ''
            },
            indexData: 0,
            updateID: '',
            oldImage: '',
            options: [
                { value: null, text: 'Please select an option' },
                { value: 'local', text: 'Local' },
                { value: 'export', text: 'Export' }
            ],
            viewData: {
                name: '',
                type: ''
            }
        }
    },
    mounted() {
        this.getClient();
        
    },
    methods: {
        getClient() {
            axios.get(`/api/private/client`)
            .then((response) => {
                this.clientData=response.data;
            })
            .catch((error) => {})
        },
        closeClient() {
            this.$bvModal.hide('modal-center');
            this.clientDetails = {
                client_name: '',
                client_type: ''
            }
            this.updateID = '';

            this.clientNameError = '';
            this.errorsName = false;
            this.clientTypeError = '';
            this.errorsType = false;
        },
        saveClient() {
            let _self = this;

            if(_self.updateID === "" || _self.updateID === null){
                if(_self.clientDetails.client_name === '' || _self.clientDetails.client_type === '' || _self.clientDetails.client_type === null) {
                    if(_self.clientDetails.client_name === '') {
                        _self.clientNameError = 'This field are required!';
                        _self.errorsName = true;
                    }
                    if(_self.clientDetails.client_type === '' || _self.clientDetails.client_type === null) {
                        _self.clientTypeError = 'This field are required!';
                        _self.errorsType = true;
                    }
                }
                else{
                    axios.post(`/api/private/client`, _self.clientDetails)
                    .then((response) => {
                        _self.getClient();
                        _self.closeClient();
                    })
                    .catch((error) => {});
                }
            }

            else {
                if(_self.clientDetails.client_name === '' || _self.clientDetails.client_type === '' || _self.clientDetails.client_type === null) {
                    if(_self.clientDetails.client_name === '') {
                        _self.clientNameError = 'This field are required!';
                        _self.errorsName = true;
                    }
                    if(_self.clientDetails.client_type === '' || _self.clientDetails.client_type === null) {
                        _self.clientTypeError = 'This field are required!';
                        _self.errorsType = true;
                    }
                }
                else{
                    axios.put(`/api/private/client/` + _self.updateID, _self.clientDetails)
                    .then((response) => {
                        _self.getClient();
                        _self.closeClient();
                    })
                    .catch((error) => {});
                }
            }

        },
        updateClient(data) {

            let _self = this;

            _self.modalTitle = "Update School"

            _self.$bvModal.show('modal-center');


            _self.clientDetails.client_name = data.client_name;
            _self.clientDetails.client_type = data.client_type;

            _self.updateID = data.id

        },
        hideConfirmation() {
            this.$bvModal.hide('modal-confirm-delete');
        },
        deleteClient() {
            let _self = this;
            axios.post('/api/private/client/delete', _self.deleteData)
            .then((response) => {
                _self.clientData.splice(_self.indexData, 1);
                _self.hideConfirmation();
            })
            .catch((error) => {
                console.log(error);
            });
        },
        viewRecord(data) {
            this.$bvModal.show('view-client');
            this.viewData.name = data.client_name;
            this.viewData.type = data.client_type;
        }
    }
}
</script>





<style lang="scss" scoped>
    .client .v-input.v-text-field.v-text-field--single-line.v-input--hide-details.theme--light {
        margin: 0px;
        padding: 0px;
        margin-right: 10px;
    }

    td { 
        i {
            cursor: pointer;
        }
    }

    .v-card__title {
        background: #dce6ec;
        border-radius: 5px;
    }

    td a {
        vertical-align: super;
        background: #55a9ff;
        padding: 2px 10px;
        color: white !important;
        margin-top: 10px;
        border-radius: 2px;
        transition: .3s;
        text-decoration: none !important;
    }

    @media screen and (max-width: 991px) {
    
    .client {
        
        .v-card__title {
            padding: 15px 0px;
        }

        .main-action .btn-primary {
            width: 100% !important;
            margin-top: 10px !important;
        }

        td button {
            margin: 5px 0 !important;
            width: 100%;
        }

    }
}
</style>
