import axios from "axios"

export default {
    namespaced: true,
    state: () => ({
        orgs: [],
        org_loading: true,
        orgs_length: 1,
        org_count: 0,
        org_view: '',
        org_user: [],
        org_user_length: 0,
        org_order: '',
        order_month: 0,
        order_year: 0
    }),

    getters: {
        orgs: (state) => state.orgs,
        org_loading: (state) => state.org_loading,
        orgs_length: (state) => state.orgs_length,
        org_count: (state) => state.org_count,
        org_view: (state) => state.org_view,
        org_user: (state) => state.org_user,
        org_user_length: (state) => state.org_user_length,
        order_month: (state) => state.order_month,
        order_year: (state) => state.order_year
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
        },
        ORG_COUNT(state, payload) {
            state.org_count = payload
        },
        ORG_LOADING(state, status) {
            state.org_loading = status
        },
        ORG_VIEW(state, payload) {
            state.org_view = {
                name: payload.name,
                code: payload.code,
                mobileno: payload.mobileno,
                user_count: payload.user.length,
                status: payload.isactive,
                line_token: payload.line_notify_token,
                created: new Date(payload.created).toLocaleDateString()
            }
        },
        USER_ONLINE(state, payload) {
            let users = [];
            payload.forEach((item) => {
                let diff = item.user_activity !== null ? new Date(item.user_activity.modified).getTime() - new Date().getTime() : -60001

                users.push({
                    'id': item.id,
                    'name': {'fullname': item.fullname, 'nickname': item.name, 'username': item.username}, 
                    'mobileno': item.mobileno, 
                    'type': item.type, 
                    'role': item.role !== null ? item.role.name : null, 
                    'created': new Date(item.created).toLocaleDateString(),
                    'online': diff > -60000 ? true : false,
                    'status': item.isactive,
                    'employee': item.isemployee,
                    'freelance': item.isfreelance
                })
            })

            state.org_user = users
            state.org_user_length = users.length
        },
        SET_USER_LENGTH(state, payload) {
            state.org_user_length = payload.length
        },
        ORDER_MONTH(state, payload) {
            state.order_month = payload
        },
        ORDER_YEAR(state, payload) {
            state.order_year = payload
        },
        ORDER_UPDATE(state, payload) {
            let order = payload - state.order_month
            let order_year = order > 0 ? state.order_year + order : state.order_year

            state.order_month = payload
            state.order_year = order_year
        },
        CLEAR_ORG(state) {
            state.org_view = ''
            state.org_user = []
            state.org_user_length = 0
            state.org_order = ''
            state.order_month = 0
            state.order_year = 0
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
        },
        orgCount({commit}) {
            axios.get(`${process.env.MIX_API_URL}/org/count`,{
                headers: {
                    'Authorization': `bearer ${localStorage.getItem('_tk')}`
                }
            })
            .then((res) => {
                commit('ORG_COUNT', res.data.orgs)
            })
            .catch((e) => {
                console.log(e)
            })
        },
        async orgView({commit}, payload) {
            commit('CLEAR_ORG')
            commit('ORG_LOADING', true)
            let res = await axios.get(`${process.env.MIX_API_URL}/org/view/${payload.id}`,{
                        headers: {
                            'Authorization': `bearer ${localStorage.getItem('_tk')}`
                        }
            })

            if(!res.data.error) {
                commit('ORG_LOADING', false)
                commit('ORG_VIEW', res.data.org)
                commit('USER_ONLINE', res.data.org.user)
                commit('ORDER_MONTH', res.data.order_month)
                commit('ORDER_YEAR', res.data.order_year)
            }
        },
        async userOnline({commit}, payload) {
            let res = await axios.get(`${process.env.MIX_API_URL}/org/view/online/${payload.id}`, {
                headers: {
                    'Authorization': `bearer ${localStorage.getItem('_tk')}`
                }
            })

            if(!res.data.error) {
                commit('USER_ONLINE', res.data.user)
            }
        },
        async orderUpdate({commit}, payload) {
            let res = await axios.get(`${process.env.MIX_API_URL}/org/view/order/${payload.id}`, {
                headers: {
                    'Authorization': `bearer ${localStorage.getItem('_tk')}`
                }
            })

            if(!res.data.error) commit('ORDER_UPDATE', res.data.order)
        }
    }
}