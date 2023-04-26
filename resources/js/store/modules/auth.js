import axios from "axios";

const state = {
    token:'',
};

// getters
const getters = {
    getToken(){
        return state.token;
    }
}

// actions
const actions = {

    async login({commit}, payload){
        const  _res = await axios.post('/api/login', payload);
        const res = await _res.data;
        if(!res.success){
            throw new Error('Unexpected error during login');
        }
        commit('setToken', res.token);
    },

    loadToken({commit}){
        let token = localStorage.getItem('token');
        if(token == null || token === 'null'){
            token = "";
        }
        commit('setToken', token);
    },

    async logout({commit}){
        axios.defaults.headers.common['Authorization'] = `Bearer ${state.token}`;
        const _res = await axios.post('/api/logout');
        const res = await _res.data;
        if(!res.success){
            throw new Error('Unexpected error during logout');
        }
        commit('deleteToken');
    }
}

// mutations
const mutations = {
    setToken(state, token){
        localStorage.setItem('token', token);
        state.token = token;
    },
    deleteToken(state){
        localStorage.removeItem('token');
        state.token = '';
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
