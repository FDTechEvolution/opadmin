import Vue from 'vue'
import Vuex from 'vuex'
import axios from "axios"

Vue.use(Vuex)

import auth from './modules/auth.js'
import org from './modules/org.js'
import user from './modules/user.js'

const store = new Vuex.Store({
    modules: {
        auth,
        org,
        user
    }
})

export default store;