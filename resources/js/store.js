export default {
    state : {
        user: ''
    },
    mutations: {
        getUser(state, posts) {
            state.user = posts
        }
    },
    actions : {
        loadData({commit}) {
            axios.get('/api/private/user_info').then((response) => {
                commit('getUser', response.data)
            });
        }
    },
    getters : {
        user (state) {
            return state.user;
        }
    }
}