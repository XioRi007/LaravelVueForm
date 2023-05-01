import axios from "axios";
const defaultForm = {
    id:null,
    firstName: '',
    lastName: '',
    birthdate: '',
    reportSubject: '',
    country: 'Ukraine',
    phone: '+',
    email: '',
    company:'',
    position:'',
    about:'',
    photo:''}

const state = {
    count:0,
    list:[],
    form: defaultForm
}
// getters
const getters = {
    getMembers(){
        return state.list;
    },
    getMembersCount(){
        return state.count;
    },
    getPersonal(){
        return {
            id: state.form.id,
            firstName: state.form.firstName,
            lastName: state.form.lastName,
            birthdate: state.form.birthdate,
            reportSubject: state.form.reportSubject,
            country: state.form.country,
            phone: state.form.phone,
            email: state.form.email,
        };
    },
    getId(){
        return state.form.id;
    },
    getDetailed(){
        return {
            company: state.form.company,
            position: state.form.position,
            about: state.form.about,
            photo: state.form.photo
        };
    }
}

// actions
const actions = {
    /**
     * Load to the state members list.
     * @param commit
     * @param fields
     * @param showHidden
     * @returns {Promise<void>}
     */
    async fetchMembers({commit}, {fields, showHidden = false}){
        let url = `/api?fields=${JSON.stringify(fields)}`;
        if(showHidden){
            url = url + `&showHidden=true`;
        }
        const _res = await axios.get(url);
        let res = _res.data;
        commit('setMembers', res);
    },

    /**
     * Deletes the user
     * @param rootGetters
     * @param id
     * @returns {Promise<void>}
     */
    async delete({rootGetters}, id){
        const token = rootGetters['auth/getToken'];
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        const _res = await axios.delete(`/api/${id}`);
        const res = await _res.data;
        if(!res.success){
            console.log(res);
            throw new Error(res.message);
        }
    },

    /**
     * Loads to state member information
     * @param commit
     * @param fields
     * @returns {Promise<void>}
     */
    async loadMember({commit}, fields){
        const url = `/api/${state.form.id}?fields=${JSON.stringify(fields)}`;
        const _res = await axios.get(url);
        const res = _res.data;
        if(res.error){
            throw new Error(res.error);
        }
        for (const [name, value] of Object.entries(res)) {
            commit('setForm', {
                name,
                value: value !== 'null' ? value: ''
            })
        }
    },

    /**
     * Updating member's detail info
     * @param payload id and data to update
     * @returns {Promise<void>}
     */
    async update({}, payload){
        let formData = new FormData();
        for (const property in payload) {
            if(payload[property] || typeof payload[property] === "boolean"){
                formData.append(property, payload[property]);
            }
        }
        if(payload.hasOwnProperty('photo') && typeof payload['photo'] === 'string'){
            formData.delete('photo');
        }
        formData.delete('id');
        formData.append('_method', 'PUT');
        try{
            await axios.post(`/api/${payload.id}`, formData);
        }catch (e) {
            if(e.response.data.errors){
                throw e.response.data.errors;
            }
            else{
                throw e;
            }
        }
    },

    /**
     * Register member
     * @param rootGetters
     * @returns {Promise<void>}
     */
    async registerParticipant({rootGetters}){
        let formData = new FormData();
        for (const property in rootGetters['members/getPersonal']) {
            formData.append(property, state.form[property]);
        }
        formData.delete('id');

        try{
            await axios.post(`/api`, formData);
        }catch (e) {
            if(e.response.data.errors){
                throw e.response.data.errors;
            }
            else{
                throw e;
            }
        }
    },

    /**
     * Load to the state members count
     * @param commit
     * @returns {Promise<void>}
     */
    async fetchMembersCount({commit}){
        const _res = await axios.get(`/api/count`);
        const res = await _res.data;
        commit('setMembersCount', res.count);
    }
}

// mutations
const mutations = {
    setMembers(state, payload){
        state.list = payload;
    },
    setMembersCount(state, payload){
        state.count = payload;
    },
    setForm(state, payload){
        state.form[payload.name] = payload.value;
    },
    clearForm(state){
        Object.assign(state, defaultForm);
    }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
