import axios from "axios"

export default {
    namespaced: true,
    state: () => ({
        users: [],
        users_length: 1,
        user_count: 0,
        user_online: 0,
    }),

    getters: {
        users: (state) => state.users,
        users_length: (state) => state.users_length,
        user_count: (state) => state.user_count,
        user_online: (state) => state.user_online
    },

    mutations: {
        GET_USERS(state, payload) {
            let users = []
            payload.forEach((item) => {
                users.push({
                    'id': item.id,
                    'name': {'fullname': item.fullname, 'nickname': item.name, 'username': item.username}, 
                    'mobileno': item.mobileno, 
                    'type': item.type, 
                    'org': item.org !== null ? item.org.name : null, 
                    'role': item.role !== null ? item.role.name : null, 
                    'created': new Date(item.created).toLocaleDateString(),
                    'status': item.isactive,
                    'employee': item.isemployee,
                    'freelance': item.isfreelance
                })
            })

            state.users = users
            state.users_length = users.length
        },
        SET_USER_LENGTH(state, payload) {
            state.users_length = payload.length
        },
        USER_COUNT(state, payload) {
            state.user_count = payload
        },
        USER_ONLINE(state, payload) {
            state.user_online = payload
        }
    },
    
    actions: {
        async getUsers({commit}) {
            let response = await axios.get(`${process.env.MIX_API_URL}/user/get`, {
                headers: {
                    'Authorization': `bearer ${localStorage.getItem('_tk')}`
                }
            })

            commit('GET_USERS', response.data.user)
        },
        updateStatus({dispatch}, payload) {
            axios.get(`${process.env.MIX_API_URL}/user/status/${payload.user_id}`, {
                headers: {
                    'Authorization': `bearer ${localStorage.getItem('_tk')}`
                }
            })
            .then((res) => {
                if(!res.data.error) dispatch('getUsers')
            })
            .catch((e) => {
                console.log(e)
            })
        },
        userCount({commit}) {
            axios.get(`${process.env.MIX_API_URL}/user/count`, {
                headers: {
                    'Authorization': `bearer ${localStorage.getItem('_tk')}`
                }
            })
            .then((res) => {
                commit('USER_COUNT', res.data.users)
            })
            .catch((e) => {
                console.log(e)
            })
        },
        userOnline({commit}) {
            axios.get(`${process.env.MIX_API_URL}/user/online`, {
                headers: {
                    'Authorization': `bearer ${localStorage.getItem('_tk')}`
                }
            })
            .then((res) => {
                commit('USER_ONLINE', res.data.user_online)
            })
            .catch((e) => {
                console.log(e)
            })
        }
    }
}