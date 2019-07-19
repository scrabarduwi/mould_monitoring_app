<template>
    <div class="dashboard">
        <b-row>
            <b-col cols="12" sm="12" lg="2" class="side-col hide">
                <div class="side-drawer">
                    <div style="text-align:right;">
                        <a class="close-buton" @click="closeMenu">Close</a>
                    </div>

                    <div class="system-name">Mould Monitoring</div>
                    <div class="user-img">
                        <img src="/img/user.png" width="100%">
                    </div>
                    <div class="user-details">
                        <div class="user-name">{{ $store.state.user.name }}</div>
                    </div>
                    <hr>
                    <div class="main-menu">
                        <ul>
                            <li v-if="$store.state.user.roles === 'super-admin'"><a @click.prevent="goMenu('superadmin')">Users</a></li>
                            <li><a @click.prevent="goMenu('dashboard')">Dashboard</a></li>
                            <li><a @click.prevent="goMenu('mould')">Mould</a></li>
                            <li><a @click.prevent="goMenu('client')">Client</a></li>
                            <li><a @click.prevent="goMenu('ring-category')">Ring Category</a></li>
                            <li><a v-b-modal.modal-confirm-logout @click.prevent=''>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </b-col>
            <b-col cols="12" sm="12" lg="10">
                <div class="dashboard-header">
                    <i aria-hidden="true" class="v-icon material-icons" @click="openMenu">storage</i>
                </div>
                <div class="dashboard-content">
                    <router-view></router-view>
                </div>
            </b-col>
        </b-row>
        <b-modal id="modal-confirm-logout" centered ref="modal" title="Logout">
            Are you sure you want to logout?
            
            <template slot="modal-footer" slot-scope="">
                <button class="btn btn-primary" @click="logout">Yes</button>
                <button class="btn btn-secondary" @click="hideConfirmation">No</button>
            </template>
        </b-modal>
    </div>
</template>

<script>
export default {
    methods: {
        logout() {
            let _self = this;
            axios.post('/logout')
            .then((response) => {
                location.href = '/login';
            })
            .catch((error) => { });
        },
        openMenu() {
            document.getElementsByClassName('side-col')[0].classList.add('show');
        },
        closeMenu() {
            document.getElementsByClassName('side-col')[0].classList.remove('show');
        },
        hideConfirmation() {
            this.$bvModal.hide('modal-confirm-logout');
        },
        goMenu(route) {
            this.$router.push(route);
            this.closeMenu();
        }
    },
    mounted() {
        console.log(this);
        
    },
    created() {
        this.$store.dispatch('loadData')
    }
}
</script>

<style lang="scss" scoped>

.dashboard-header i {
    color: white;
    padding: 5px;
    font-size: 35px;
    cursor: pointer;
    display: none;
}

.close-buton {
    display: none;
}

@media screen and (max-width: 991px) {

    
    .close-buton {
        display: inline-block;
        padding: 3px 15px;
        margin-right: 5px;
        background: #ff5858;
        margin-top: 5px;
        color: white !important;
        font-size: 10px;
        cursor: pointer;
    }

    .side-col {
        position: absolute;
        z-index: 1;
        background: rgba(0,0,0,0.5);
        height: 100%;
        max-width: 0%;
        overflow: hidden;

        .side-drawer {
            height: 100%;
            background: white;
            transition: .5s;
            max-width: 0px;
            overflow: auto;
        }

        &.show {
            max-width: 100%;
            overflow: hidden;

            .side-drawer {
                max-width: 255px;
            }
        }

    }

    

    .dashboard-header i {
        color: white;
        padding: 5px;
        font-size: 35px;
        cursor: pointer;
        display: inline-block;
    }
}
</style>
