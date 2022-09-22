export default {
    namespaced: true,
    state: () => ({
        users: [],
        users_length: 1
    }),

    getters: {
        users: (state) => state.users,
        users_length: (state) => state.users_length
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
        }
    }
}