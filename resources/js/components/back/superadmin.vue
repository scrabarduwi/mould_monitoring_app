<template>
    <div class="superadmin">
        <div v-if="$store.state.user.roles === 'super-admin'">
            <h1>Administrator Panel</h1>
            <hr/>
            <div class="list-of-user">
                <table>
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users" :key="user.id" :class="user.roles">
                            <td>{{ user.email }}</td>
                            <td>
                                <span class="s-admin" v-if="user.roles === 'super-admin'">SUPER ADMIN ACCOUNT</span>
                                <span class="admin" v-if="user.roles === 'admin'">ADMIN ACCOUNT</span>
                                <span class="admin" v-if="user.roles === 'officer'">OFFICER ACCOUNT</span>
                            </td>
                            <td>
                                <button class="officer-set" v-if="user.roles === 'admin'" @click="updateUser('officer',user.id)">SET AS OFFICER</button>
                                <button class="admin-set" v-if="user.roles === 'officer'" @click="updateUser('admin',user.id)">SET AS ADMIN</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div v-if="$store.state.user.roles !== 'super-admin'">Page Not Found</div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            users:[]
        }
    },
    mounted() {
        this.getUser();
    },
    methods: {
        getUser() {
            axios.get('/api/private/user')
            .then((response) => {
                this.users = response.data;
            })
        },
        updateUser(roles, id) {
            axios.post('/api/private/user/' + roles + '/' + id)
            .then((response)=>{
                this.getUser();
            });
        }
    }
}
</script>

<style lang="scss" scoped>
span.admin {
    padding: 1px 10px;
    display: inline-block;
    color: gray;
}

span.s-admin {
    padding: 1px 10px;
    display: inline-block;
    color: gray;
}

.list-of-user table {
    width: 100%;

    thead th {
        background: #3f3f3f;
        padding: 5px 10px;
        color: white;
    }
    
    tbody {
        tr {
            border-bottom: 1px solid #ccc;
        }
    }

    tbody td {
        padding: 3px 10px;
    }

    button.admin-set {
        padding: 1px 10px;
        background: #4cc767;
        color: white;
        border-radius: 3px;
        border-bottom: 3px solid #237334;
    }

    button.officer-set {
        padding: 1px 10px;
        background: orange;
        color: white;
        border-radius: 3px;
        border-bottom: 3px solid orangered;
    }
}
tr.super-admin {
    background: #ffefb3 !important;
}

tr.admin {
    background: #ddffb6 !important;
}

</style>

