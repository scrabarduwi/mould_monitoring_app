<template>
    <div class="ring-category">
        <div v-if="$store.state.user !== ''">
            <h3>{{ $route.meta.title }}</h3>
            <hr>
            <div class="main-action text-right">
                <v-card-title>
                    <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                    <button v-if="$store.state.user.roles !== 'officer'" class="btn btn-primary" v-b-modal.modal-center @click="modalTitle='New Ring Category'">ADD RING CATEGORY</button>
                </v-card-title>
            </div>

            

            <!-- Table Form :: for ring category -->
            <v-data-table :headers="headers" :items="ringData" :search="search" disable-initial-sort>
                <template v-slot:items="props">
                    <td>
                        <img v-if="props.item.ring_image !== null" :src="'/img/ring/' + props.item.ring_image" alt="" height="50px">
                        <img v-if="props.item.ring_image === null" :src="'/img/no-image.jpg'" alt="" height="50px" style="border:1px solid #ccc;">
                    </td>
                    <td>{{ props.item.ring_name }}</td>
                    <td>{{ props.item.ring_code }}</td>
                    <td>
                        <i aria-hidden="true" class="v-icon material-icons text-secondary" @click="viewCategory(props.item)">visibility</i>
                        <i v-if="$store.state.user.roles !== 'officer'" aria-hidden="true" class="v-icon material-icons text-primary" @click="updateRing(props.item)">create</i>
                        <i v-if="$store.state.user.roles !== 'officer'" aria-hidden="true" class="v-icon material-icons text-danger" v-b-modal.modal-confirm-delete @click="deleteData.deletedID=props.item.id; deleteData.deletedName=props.item.ring_image; indexData=ringData.findIndex(x => x.id === props.item.id)">delete</i>
                    </td>
                </template>
                <template v-slot:no-results>
                    <v-alert :value="true" color="error" icon="warning">
                    Your search for "{{ search }}" found no results.
                    </v-alert>
                </template>
            </v-data-table>




            <!-- Modal Form :: for ring category -->
            <b-modal id="modal-center" centered ref="modal" :title="modalTitle" no-close-on-backdrop hide-header-close>
                <form ref="form">

                    <div class="form-group">
                        <label for="ring-image">Upload Image</label>
                        <br>
                        <button class="btn btn-primary" @click.prevent="$refs.image.click()" v-model="ring_imageName">Upload</button>
                        {{ ring_imageName }}
                        <input type="file" style="display: none;" ref="image" accept="image/*" @change="onFilePicked">
                        <span class="help-block" v-if="errorsImage">{{ ringImageError }}</span>
                    </div>

                    <div class="form-group">
                        <label for="ring-name">Name</label>
                        <input type="text" name="ring-name" id="ring_name" class="form-control" v-model="ringDetails.ring_name"/>
                        <span class="help-block" v-if="errorsName">{{ ringNameError }}</span>
                    </div>

                    <div class="form-group">
                        <label for="ring-code">Code</label>
                        <input type="text" name="ring-code" id="ring_code" class="form-control" v-model="ringDetails.ring_code"/>
                        <span class="help-block" v-if="errorsCode">{{ ringCodeError }}</span>
                    </div>

                </form>
                <template slot="modal-footer" slot-scope="">
                    <button class="btn btn-primary" @click="saveRing">SAVE</button>
                    <button class="btn btn-secondary" @click="closeRing">CANCEL</button>
                </template>
            </b-modal>


            <!-- Modal :: for Deleting Data -->
            <b-modal id="modal-confirm-delete" centered ref="modal" title="Delete Ring Category">
                Are you sure you want to delete this data?
                
                <template slot="modal-footer" slot-scope="">
                    <button class="btn btn-primary" @click="deleteRing">Yes</button>
                    <button class="btn btn-secondary" @click="hideConfirmation">No</button>
                </template>
            </b-modal>

            
            <!-- Modal :: for View Data -->
            <b-modal id="view-ring-category" centered ref="modal" title="View Data">
                <b-row>
                    <b-col><b>Code: </b>{{ viewRecord.code }}</b-col>
                    <b-col><b>Name: </b>{{ viewRecord.name }}</b-col>
                </b-row>

                <div class="ring-img">
                    <img v-if="viewRecord.image !== 'none'" :src="'/img/ring/' + viewRecord.image" alt="">
                    <img v-if="viewRecord.image === 'none'" :src="'/img/no-image.jpg'" alt="">
                </div>
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
            ringImageError: '',
            ringCodeError: '',
            ringNameError: '',
            errorsImage: false,
            errorsName: false,
            errorsCode: false,
            headers: [
                { text: 'Image', value: 'ring_image' },
                { text: 'Name', value: 'ring_name' },
                { text: 'Code', value: 'ring_code' },
                { text: 'Action', value: 'action' }
            ],
            ringData: [],
            ringDetails: {
                ring_image: '',
                ring_name: '',
                ring_code: ''
            },
            ring_imageName: '',
            ring_imageFile: '',
            deleteData: {
                deletedID: '',
                deletedName: ''
            },
            indexData: 0,
            updateID: '',
            oldImage: '',
            viewRecord: {
                image: '',
                name: '',
                code: ''
            }
        }
    },
    mounted() {
        this.getData();
        
    },
    methods: {
        getData(){
            axios.get(`/api/private/ring-category`)
            .then((response) => {
                this.ringData = response.data;
            })
            .catch((error) =>{});
        },
        onFilePicked (e) {
            const files = e.target.files
            console.log(files)
            if(files[0] !== undefined) {
                this.ring_imageName = files[0].name
                if(this.ring_imageName.lastIndexOf('.') <= 0) {
                    return
                }
                const fr = new FileReader ()
                fr.readAsDataURL(files[0])
                fr.addEventListener('load', () => {
                    this.ringDetails.ring_image = fr.result
                    this.ring_imageFile = files[0].type
                })
            } else {
                this.ring_imageName = ''
                this.ring_imageFile = ''
                this.ringDetails.ring_image = ''
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
        closeRing() {
            this.$bvModal.hide('modal-center');
            this.ringDetails = {
                ring_image: '',
                ring_name: '',
                ring_code: ''
            }
            this.ring_imageName = '';
            this.updateID = '';
            this.oldImage = '';
        },
        saveRing() {
            let _self = this;

            if(_self.updateID === '' || _self.updateID === null) {
                if(_self.ringDetails.ring_name === '' || _self.ringDetails.ring_code === '') {
                    if(_self.ringDetails.ring_name === '') {
                        _self.errorsName = true;
                        _self.ringNameError = 'This field are required.'
                    }
                    if(_self.ringDetails.ring_code === '') {
                        _self.errorsCode = true;
                        _self.ringCodeError = 'This field are required.'
                    }
                }
                else {
                    axios.post(`/api/private/ring-category`, _self.ringDetails)
                    .then((response) => {
                        var file_type='';
                            
                        if(this.ring_imageName !== ""){
                            if(this.ring_imageFile === "image/png"){
                                file_type='png';
                            }
                            else {
                                file_type='jpg';
                            }
                            
                        }
                        else {
                        }

                        this.ring_imageName = "";
                        this.ring_imageFile = "";
                        this.ringDetails = {
                            ring_image: '',
                            ring_name: '',
                            ring_code: ''
                        }
                        
                        this.$bvModal.hide('modal-center')
                        this.getData();
                    })
                    .catch((error) => {
                        console.log(error);
                    });
                }
            }
            else{
                if(this.ringDetails.ring_name !== "" || _self.ringDetails.ring_code !== '') {

                    this.errorsName = false;
                    this.ringNameError = "";

                    this.errorsCode = false;
                    this.ringCodeError = "";
                    
                    axios.put('/api/private/ring-category/' + this.updateID + '/' + this.oldImage, this.ringDetails)
                    .then((response) => {
                        var file_type='';
                        
                        if(this.ring_imageName !== ""){
                            if(this.ring_imageFile === "image/png"){
                                file_type='png';
                            }
                            else {
                                file_type='jpg';
                            }
                            
                        }
                        else {
                        }
                        this.closeRing();
                        
                        this.getData();
                    })
                    .catch((error) => {
                        console.log(error.response);
                    });
                }
                else {
                    this.errorsName = true;
                    this.schoolNameError = "This is a required field."; 
                }
            }
            
        },
        hideConfirmation() {
            this.$bvModal.hide('modal-confirm-delete');
        },
        deleteRing() {
            let _self = this;
            axios.post('/api/private/ring-category/delete', _self.deleteData)
            .then((response) => {
                _self.ringData.splice(_self.indexData, 1);
                _self.hideConfirmation();
            })
            .catch((error) => {
                console.log(error);
            });
        },
        updateRing(data) {
            let _self = this;

            _self.modalTitle = "Update Ring Category"

            _self.$bvModal.show('modal-center');


            _self.ringDetails.ring_name = data.ring_name;
            _self.ringDetails.ring_code = data.ring_code;

            _self.toDataURL('/img/ring/' + data.ring_image, function(dataURL) {
                _self.ringDetails.ring_image = dataURL;
            });

            _self.ring_imageName = data.ring_image;
            _self.updateID = data.id;

            if(_self.oldImage !== "") {
                _self.oldImage = data.ring_image.split('.')[0];
            }
            else{
                 _self.oldImage = 'none';
            }

        },
        viewCategory(data) {
            this.$bvModal.show('view-ring-category');
            this.viewRecord.name = data.ring_name;
            this.viewRecord.code = data.ring_code; 

            if(data.ring_image === null) {
                this.viewRecord.image = 'none';
            }
            else{
                this.viewRecord.image = data.ring_image;
            }
        }
    }
}
</script>





<style lang="scss" scoped>
    .ring-category .v-input.v-text-field.v-text-field--single-line.v-input--hide-details.theme--light {
        margin: 0px;
        padding: 0px;
        margin-right: 10px;
    }

    td { 
        i {
            cursor: pointer;
        }
    }

    .ring-img {
        text-align: center;
        padding: 10px;
    }

    .ring-img img {
        border: 4px solid #3f3f3f;
        margin-top: 20px;
        width: 100%;
    }

    .v-card__title {
        background: #dce6ec;
        border-radius: 5px;
    }

    @media screen and (max-width: 991px) {
    
    .ring-category {
        
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
