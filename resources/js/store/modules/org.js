export default {
    namespaced: true,
    state: () => ({
        orgs: [],
        orgs_length: 1,
    }),

    getters: {
        orgs: (state) => state.orgs,
        orgs_length: (state) => state.orgs_length
    },

    mutations: {
        GET_ORGS(state, payload) {
            let orgs = []
            payload.forEach((item) => {
                orgs.push({
                    'id': item.id,
                    'name': item.name, 
                    'mobileno': item.mobileno, 
                    'code': item.code, 
                    'created': new Date(item.created).toLocaleDateString(),
                    'user_count': item.user_count,
                    'status': item.isactive
                })
            })

            state.orgs = orgs
            state.orgs_length = orgs.length
        },
        SET_ORG_LENGTH(state, payload) {
            state.orgs_length = payload.length
        }
    },
    
    actions: {
        async getOrgs({commit}) {
            let response = await axios.get(`${process.env.MIX_API_URL}/org/get`, {
                headers: {
                    'Authorization': `bearer ${localStorage.getItem('_tk')}`
                }
            })

            commit('GET_ORGS', response.data.org)
        },
        updateOrg({commit}, payload) {
            axios.post(`${process.env.MIX_API_URL}/org/update`, {
                'org_id': payload.org_id,
                'name': payload.name,
                'mobileno': payload.mobileno,
                'code': payload.code
            }, {
                headers: {
                    'Authorization': `bearer ${localStorage.getItem('_tk')}`
                }
            })
            .then((res) => {

            })
            .catch((e) => {

            })
        },
        updateStatus({dispatch}, payload) {
            axios.get(`${process.env.MIX_API_URL}/org/status/${payload.org_id}`,{
                headers: {
                    'Authorization': `bearer ${localStorage.getItem('_tk')}`
                }
            })
            .then((res) => {
                if(!res.data.error) dispatch('getOrgs')
            })
            .catch((e) => {
                console.log(e)
            })
        }
    }
}