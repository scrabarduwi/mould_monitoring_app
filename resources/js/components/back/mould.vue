<template>
    <div class="mould">
        <div v-if="$store.state.user !== ''">
            <h3>{{ $route.meta.title }}</h3>
            <hr>
            <div class="main-action text-right">
                <v-card-title>
                    <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                    <button v-if="$store.state.user.roles !== 'officer'" class="btn btn-primary" v-b-modal.modal-center @click="modalTitle='New Mould'">ADD MOULD</button>
                </v-card-title>
            </div>

            

            <!-- Table Form :: for client -->
            <v-data-table :headers="headers" :items="mouldData" :search="search" disable-initial-sort>
                <template v-slot:items="props">
                    <td>{{ props.item.client_name }}</td>
                    <td>{{ props.item.client_type }}</td>
                    <td><img v-if="props.item.mould_image !== null" :src="'/img/mould/' + props.item.mould_image" height="50px"/>
                    <img v-if="props.item.mould_image === null" :src="'/img/no-image.jpg'" height="50px" style="border:1px solid #ccc;"/></td>
                    <td>{{ props.item.mould_details }}</td>
                    <td>{{ props.item.ring_code }}</td>
                    <td>{{ props.item.ring_name }}</td>
                    <td><span class="rack-no">{{ props.item.rack_no }}</span></td>
                    <td>
                        <i aria-hidden="true" class="v-icon material-icons text-secondary" @click="viewRecord(props.item)">visibility</i>
                        <i v-if="$store.state.user.roles !== 'officer'" aria-hidden="true" class="v-icon material-icons text-primary" @click="updateMould(props.item)">create</i>
                        <i v-if="$store.state.user.roles !== 'officer'" aria-hidden="true" class="v-icon material-icons text-danger" v-b-modal.modal-confirm-delete @click="deleteData.deletedID=props.item.id; deleteData.deletedName=props.item.mould_image; indexData=mouldData.findIndex(x => x.id === props.item.id)">delete</i>
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
                        <label for="client-name">Client Name</label>
                        <b-form-select v-model="mouldDetails.client_id">
                            <option v-for="opt in options" :key="opt.id" :value="opt.id">{{ opt.client_name }}</option>
                        </b-form-select>
                        <span class="help-block" v-if="errorsClient">{{ clientError }}</span>
                    </div>

                    <div class="form-group">
                        <label for="mould-img">Mould Image</label>
                        <br>
                        <button class="btn btn-primary" @click.prevent="$refs.image.click()" :v-model="mould_imageName">Upload</button>
                        {{ mould_imageName }}
                        <input type="file" style="display: none;" ref="image" accept="image/*" @change="onFilePicked">
                        <span class="help-block" v-if="errorsImage">{{ mouldImageError }}</span>
                    </div>

                    <div class="form-group">
                        <label for="ring-name">Ring Name</label>
                        <b-form-select v-model="mouldDetails.ring_id">
                            <option v-for="ring in rings" :key="ring.id" :value="ring.id">{{ ring.ring_name }} - ({{ ring.ring_code }})</option>
                        </b-form-select>
                        <span class="help-block" v-if="errorsRing">{{ ringError }}</span>
                    </div>

                    <div class="form-group">
                        <label for="mould-details">Mould Details</label>
                        <b-form-textarea id="textarea" v-model="mouldDetails.mould_details"></b-form-textarea>
                        <span class="help-block" v-if="errorsDetails">{{ detailsError }}</span>
                    </div>
                    
                    <div class="form-group">
                        <label for="rack-no">Rack No.</label>
                        <input type="number" name="rack-no" id="rack_no" class="form-control" v-model="mouldDetails.rack_no"/>
                        <span class="help-block" v-if="errorsRack">{{ rackError }}</span>
                    </div>

                </form>
                <template slot="modal-footer" slot-scope="">
                    <button class="btn btn-primary" @click="saveMould">SAVE</button>
                    <button class="btn btn-secondary" @click="closeMould">CANCEL</button>
                </template>
            </b-modal>


            <!-- Modal :: for Deleting Data -->
            <b-modal id="modal-confirm-delete" centered ref="modal" title="Delete Client">
                Are you sure you want to delete this data?
                
                <template slot="modal-footer" slot-scope="">
                    <button class="btn btn-primary" @click="deleteMould">Yes</button>
                    <button class="btn btn-secondary" @click="">No</button>
                </template>
            </b-modal>


            <!-- Vlient :: for View Data -->
            <b-modal id="view-client" centered ref="modal" title="View Data">
                <b-row>
                    <b-col><b>Client Name: </b>{{ viewData.client_name }}</b-col>
                    <b-col><b>Client Type: </b>{{ viewData.client_type }}</b-col>
                </b-row>
                <b-row>
                    <b-col><b>Mould Details: </b>{{ viewData.mould_details }}</b-col>
                </b-row>
                <div class="mould-image">
                    <img v-if="viewData.mould_image !== null" :src="'/img/mould/' + viewData.mould_image"/>
                    <img v-if="viewData.mould_image === null" :src="'/img/no-image.jpg'"/>
                </div>
                <b-row>
                    <b-col><b>Ring Code: </b>{{ viewData.ring_code }}</b-col>
                    <b-col><b>Ring Name: </b>{{ viewData.ring_name }}</b-col>
                </b-row>
                <b-row>
                    <b-col><b>Rack No.: </b>{{ viewData.rack_no }}</b-col>
                </b-row>
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
            clientError: '',
            mouldImageError: '',
            ringError: '',
            detailsError: '',
            rackError: '',
            errorsClient: false,
            errorsImage: false,
            errorsRing: false,
            errorsDetails: false,
            errorsRack: false,
            headers: [
                { text: 'Client Name', value: 'client_name' },
                { text: 'Client Type', value: 'client_type' },
                { text: 'Mould Image', value: 'mould_image' },
                { text: 'Mould Details', value: 'mould_details' },
                { text: 'Ring Code', value: 'ring_code' },
                { text: 'Ring Name', value: 'ring_name' },
                { text: 'Rack No.', value: 'rack_no' },
                { text: 'Action', value: 'action' }
            ],
            mouldData: [],
            mouldDetails: {
                mould_image: '',
                mould_details: '',
                rack_no: '',
                client_id: '',
                ring_id: ''
            },
            mould_imageName: '',
            mould_imageFile: '',
            options: [],
            rings: [],
            updateID: '',
            deleteData: {
                deletedID: '',
                deletedName: ''
            },
            indexData: 0,
            oldImage: '',
            viewData: {
                client_name: '',
                client_type: '',
                mould_image: '',
                mould_details: '',
                ring_code: '',
                ring_name: '',
                rack_no: ''
            }
        }
    },
    mounted() {
        axios.get(`/api/private/client`)
        .then((response) => {
            this.options = response.data;
        })

        axios.get(`/api/private/ring-category`)
        .then((response) => {
            this.rings = response.data;
        })

        this.getMould();
        
    },
    methods: {
        getMould() {
            axios.get(`/api/private/mould`)
            .then((response) => {
                this.mouldData = response.data;
            })
        },
        onFilePicked (e) {
            const files = e.target.files
            console.log(files)
            if(files[0] !== undefined) {
                this.mould_imageName = files[0].name
                if(this.mould_imageName.lastIndexOf('.') <= 0) {
                    return
                }
                const fr = new FileReader ()
                fr.readAsDataURL(files[0])
                fr.addEventListener('load', () => {
                    this.mouldDetails.mould_image = fr.result
                    this.mould_imageFile = files[0].type
                })
            } else {
                this.mould_imageName = ''
                this.mould_imageFile = ''
                this.mouldDetails.mould_image = ''
            }
        },
        toDataURL(src, callback) {
            var image = new Image();
            image.crossOrigin = 'Anonymous';
         
            image.onload = function() {
                var canvas = document.createElement('canvas');
                var context = canvas.getContext('2d');
                canvas.height = this.naturalHeight;
                canvas.width = this.naturalWidth;
                context.drawImage(this, 0, 0);
                var dataURL = canvas.toDataURL('image/png');
                callback(dataURL);
            };

            image.src = src;
        },
        closeMould() {
            this.$bvModal.hide('modal-center');
            this.mouldDetails = {
                mould_image: '',
                mould_details: '',
                rack_no: '',
                client_id: '',
                ring_id: ''
            }
            this.mould_imageName = '';
            this.updateID = '';
        },
        saveMould() {
            let _self = this;

            _self.clientError = '';
            _self.errorsClient = false;
            _self.ringError = '';
            _self.errorsRing = false;
            _self.rackError = '';
            _self.errorsRack = false;

            if(_self.updateID === "") {

                if(_self.mouldDetails.client_id === '' || _self.mouldDetails.client_id === null ||
                   _self.mouldDetails.ring_id === '' || _self.mouldDetails.ring_id === null ||
                   _self.mouldDetails.rack_no === '' || _self.mouldDetails.rack_no === null)
                {
                    if(_self.mouldDetails.client_id === '' || _self.mouldDetails.client_id === null) 
                    {
                        _self.clientError = 'This field are required!';
                        _self.errorsClient = true;
                    }
                    if(_self.mouldDetails.ring_id === '' || _self.mouldDetails.ring_id === null) 
                    {
                        _self.ringError = 'This field are required!';
                        _self.errorsRing = true;
                    }
                    if(_self.mouldDetails.rack_no === '' || _self.mouldDetails.rack_no === null) 
                    {
                        _self.rackError = 'This field are required!';
                        _self.errorsRack = true;
                    }
                }

                else {
                    axios.post(`/api/private/mould`, _self.mouldDetails)
                    .then((response) => {
                        var file_type='';
                            
                        if(this.mould_imageName !== ""){
                            if(this.mould_imageFile === "image/png"){
                                file_type='png';
                            }
                            else {
                                file_type='jpg';
                            }
                            
                        }
                        else {
                        }
                        
                        this.getMould();
                        this.closeMould();
                    })
                    .catch((error) => {})
                }

            }
            else{

                if(_self.mouldDetails.client_id === '' || _self.mouldDetails.client_id === null ||
                   _self.mouldDetails.ring_id === '' || _self.mouldDetails.ring_id === null ||
                   _self.mouldDetails.rack_no === '' || _self.mouldDetails.rack_no === null)
                {
                    if(_self.mouldDetails.client_id === '' || _self.mouldDetails.client_id === null) 
                    {
                        _self.clientError = 'This field are required!';
                        _self.errorsClient = true;
                    }
                    if(_self.mouldDetails.ring_id === '' || _self.mouldDetails.ring_id === null) 
                    {
                        _self.ringError = 'This field are required!';
                        _self.errorsRing = true;
                    }
                    if(_self.mouldDetails.rack_no === '' || _self.mouldDetails.rack_no === null) 
                    {
                        _self.rackError = 'This field are required!';
                        _self.errorsRack = true;
                    }
                }

                else {
                    axios.put(`/api/private/mould/`+ _self.updateID + '/' + _self.oldImage, _self.mouldDetails)
                    .then((response) => {
                        var file_type='';
                            
                        if(_self.mould_imageName !== ""){
                            if(_self.mould_imageFile === "image/png"){
                                file_type='png';
                            }
                            else {
                                file_type='jpg';
                            }
                            
                        }
                        else {
                        }
                        
                        _self.getMould();
                        _self.closeMould();
                    })
                    .catch((error) => {})
                }

            }
        },
        hideConfirmation() {
            this.$bvModal.hide('modal-confirm-delete');
        },
        deleteMould() {
            let _self = this;
            axios.post('/api/private/mould/delete', _self.deleteData)
            .then((response) => {
                _self.mouldData.splice(_self.indexData, 1);
                _self.hideConfirmation();
            })
            .catch((error) => {
                console.log(error);
            });
        },
        updateMould(data) {
            let _self = this;

            _self.modalTitle = "Update Mould"

            _self.$bvModal.show('modal-center');


            _self.mouldDetails.client_id = data.client_id;
            _self.mouldDetails.mould_details = data.mould_details;
            _self.mouldDetails.rack_no = data.rack_no;
            _self.mouldDetails.ring_id = data.ring_id;

            this.toDataURL('/img/mould/' + data.mould_image, function(dataURL) {
                _self.mouldDetails.mould_image = dataURL;
            });

            _self.mould_imageName = data.mould_image;
            
            _self.oldImage = data.mould_image.split('.')[0];
            _self.updateID = data.id

        },
        viewRecord(data) {
            this.$bvModal.show('view-client');
            this.viewData.client_name = data.client_name;
            this.viewData.client_type = data.client_type;
            this.viewData.mould_details = data.mould_details;
            this.viewData.mould_image = data.mould_image;
            this.viewData.ring_code = data.ring_code;
            this.viewData.ring_name = data.ring_name;
            this.viewData.rack_no = data.rack_no;
        }
    }
}
</script>





<style lang="scss" scoped>
    .mould .v-input.v-text-field.v-text-field--single-line.v-input--hide-details.theme--light {
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

    .mould-image img {
        width: 100%;
        border: 4px solid #3f3f3f;
        margin: 30px 0;
    }

    @media screen and (max-width: 991px) {
    
    .mould {
        
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
