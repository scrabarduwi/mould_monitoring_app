<template>
    <div class="home">
        <h1>Dashboard</h1>
        <div v-if="$store.state.user !== ''">
            <b-row>
                <b-col>
                    <div class="monitor-card">
                        <h3>Client</h3>
                        <div class="counter">{{ counter.client }}</div>
                        <router-link to="/client" class="btn btn-primary" style="width: 100%; margin-top: 15px;">Go to Client</router-link>
                    </div>
                </b-col>
                <b-col>
                    <div class="monitor-card">
                        <h3>Ring</h3>
                        <div class="counter">{{ counter.ring }}</div>
                        <router-link to="/ring-category" class="btn btn-primary" style="width: 100%; margin-top: 15px;">Go to Ring</router-link>
                    </div>
                </b-col>
                <b-col>
                    <div class="monitor-card">
                        <h3>Mould</h3>
                        <div class="counter">{{ counter.mould }}</div>
                        <router-link to="/mould" class="btn btn-primary" style="width: 100%; margin-top: 15px;">Go to Mould</router-link>
                    </div>
                </b-col>
            </b-row>
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
            counter: {
                client: 0,
                ring: 0,
                mould: 0
            },
            client:[],
            category: [],
            mould: []
        }
    },
    mounted() {
        axios.get(`/api/private/client`)
        .then((response) => {
            this.client = response.data;
            this.counter.client = response.data.length;
        })

        axios.get(`/api/private/ring-category`)
        .then((response) => {
            this.category = response.data;
            this.counter.ring = response.data.length;
        })

        axios.get(`/api/private/mould`)
        .then((response) => {
            this.mould = response.data;
            this.counter.mould = response.data.length;
        })
    },
    methods: {

    }
}
</script>

<style lang="scss" scoped>
.home .row > div.col {
    background: transparent;
    padding: 20px 30px;
    border: 1px solid #ccc;
    margin: 20px;
}

.counter {
    font-size: 55px;
    text-align: center;
    border-top: 1px solid #ccc;
}
</style>
