// initial state
const getDefaultState = () => {
    return {
        personal:{
            id:null,
            firstName: '',
            lastName: '',
            birthdate: '',
            reportSubject: '',
            country: 'Ukraine',
            phone: '+',
            email: '',
        },
        detailed:{
            company:'',
            position:'',
            about:'',
            photo:''
        }
    }
  }
const state = getDefaultState();

// getters
const getters = {
    getPersonal(){
        return state.personal;
    },
    getId(){
        return state.personal.id;
    },
    getDetailed(){
        return state.detailed;
    }
}

// actions
const actions = {
    /**
     * Loads to state personal information
     */
    async loadPersonal({commit}){
        const fields = ['firstName', 'lastName', 'reportSubject', 'email', 'phone', 'country', 'birthdate'];
        const url = `/api/${state.personal.id}?fields=${JSON.stringify(fields)}`;
        const _res = await fetch(url);
        const res = await _res.json();
        for (const [name, value] of Object.entries(res)) {
            commit('setPersonal', {
                name,
                value
            })
        }
    },

    /**
     * Loads to state detail information
     */
    async loadDetails({commit}){
        const fields = ['about', 'company', 'photo', 'position'];
        const url = `/api/${state.personal.id}?fields=${JSON.stringify(fields)}`;
        const _res = await fetch(url);
        const res = await _res.json();
        if(res.error){
            throw new Error({message:res.error});
        }
        for (const [name, value] of Object.entries(res)) {
            commit('setDetails', {
                name,
                value: value != 'null' ? value: ''
            })
        }
    },

    /**
     * Register member
     */
    async registerParticipant(){
        let formData = new FormData();
        for (const property in state.personal) {
            formData.append(property, state.personal[property]);
        }
        formData.delete('id');
        const _res = await fetch(`/api`, {
            method:'POST',
            body:formData
        });
        const res = await _res.json();
        if(res.success){
            state.personal.id = res.id;
        }
        else{
            console.log(res);
            throw res.errors;
        }
    },

    /**
     * Updating member's personal info
     */
    async updateParticipant(){
        let formData = new FormData();
        for (const property in state.personal) {
            if(state.personal[property]){
                formData.append(property, state.personal[property]);
            }
        }
        formData.delete('id');
        formData.append('_method', 'PUT');

        const _res = await fetch(`/api/${state.personal.id}`, {
            method:"POST",
            body:formData
        });
        const res = await _res.json();
        if(!res.success){
            console.log(res);
            if(res.errors){
                throw res.errors;
            }
            throw new Error(res.error);
        }
    },

    /**
     * Updating member's detail info
     *
     */
    async updateDetails(){
        let formData = new FormData();
        for (const property in state.detailed) {
            if(state.detailed[property]){
                formData.append(property, state.detailed[property]);
            }
        }
        formData.delete('id');
        formData.append('_method', 'PUT');
        const _res = await fetch(`/api/${state.personal.id}`, {
            method:"POST",
            body:formData
        });
        const res = await _res.json();
        if(!res.success){
            console.log(res);
            if(res.errors){
                throw res.errors;
            }
            throw new Error(res.error);
        }
    }
}

// mutations
const mutations = {
    setPersonal(state, payload){
        state.personal[payload.name] = payload.value;
    },
    setDetails(state, payload){
        state.detailed[payload.name] = payload.value;
    },
    clear(state){
        Object.assign(state, getDefaultState());
    }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
