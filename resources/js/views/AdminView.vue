<script setup>
import {onBeforeMount, ref} from "vue";
import {useRouter} from "vue-router";
import {useStore} from "vuex";
import Modal from "@/components/Modal.vue";
import DeleteIcon from "@/components/icons/DeleteIcon.vue";
import EditIcon from "@/components/icons/EditIcon.vue";
import ToggleVisibilityIcon from "@/components/icons/ToggleVisibilityIcon.vue";
import EditForm from "@/components/EditForm.vue";
import Loader from "@/components/Loader.vue";
const router = useRouter();
const store = useStore();
const members = ref([]);
const keys = ref([]);
const loading = ref(true);
const unwantedKeys = ['id', 'photo', 'email'];

onBeforeMount(async()=>{
    try{
        if(store.getters["auth/getToken"] === ''){
            await router.push('/login');
        }
        await store.dispatch('members/fetchMembers', {fields:['*'], showHidden:true});
        members.value = store.getters['members/getMembers'];
        keys.value = Object.keys(members.value[0]).filter(key => !unwantedKeys.includes(key));
        loading.value = false;
    }catch (err){
        console.log(err);
        await router.push('/login');
    }
})
const logout = async () => {
    try {
        await store.dispatch('auth/logout');
        window.location.href = '/';//not router.push to rerender the header link
    } catch (err) {
        console.log(err);
        store.commit('auth/deleteToken');
        window.location.href = '/login';
    }
}
const deleteHandler = (id) => {
    return async ()=>{
        await store.dispatch('members/delete', id);
        router.go();
    }
}
const visibilityHandler = (id, hidden) => {
    return async ()=>{
        await store.dispatch('members/update', {id,hidden:!hidden});
        router.go();
    }
}
</script>

<template>
    <div class="container my-5 text-center">
        <div class="border shadow p-5">
            <h1 class="mb-5">Admin</h1>
            <Loader v-if="loading"/>
            <div v-else class="accordion accordion-flush" id="accordionExample">
                <div class="accordion-item" v-for="member in members" :key="member.id">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapse-' + member.id" aria-expanded="false" :aria-controls="'collapse-' + member.id">
                            <img :src="member.photo" class="rounded-circle object-fit-cover" width="30" height="30" alt="member photo">
                            <span class="ms-4">{{ member.email }}</span>
                            <span v-if="member.hidden" class="ms-2 badge text-bg-secondary">Hidden</span>
                        </button>
                    </h2>
                    <div :id="'collapse-' + member.id" class="accordion-collapse collapse" :data-bs-parent="'#parent-' + member.id">
                        <div class="accordion-body text-start">
                            <div class="row px-4">
                                <div class="col-6">
                                    <p v-for="field in keys"><strong>{{field}}</strong>: {{member[field]}}</p>
                                </div>
                                <div class="col-6 d-flex flex-column align-items-end">
                                    <Modal
                                        title="Delete member"
                                        :icon="DeleteIcon"
                                        :handler="deleteHandler(member.id)"
                                        text="Are you sure to delete the member?"
                                        :target-id="'DeleteModal'+member.id">
                                    </Modal>
                                    <Modal
                                        :icon="EditIcon"
                                        title="Edit member"
                                        :target-id="'EditModal'+member.id"
                                        :button="false">
                                        <EditForm :memberId="member.id" :member="member"/>
                                    </Modal>
                                    <Modal
                                        :title="member.hidden ? 'Show':'Hide' +' member'"
                                        :icon="ToggleVisibilityIcon"
                                        :handler="visibilityHandler(member.id, member.hidden)"
                                        :text="`Are you sure to ${member.hidden ? 'show' : 'hide'} the member?`"
                                        :target-id="'VisibilityModal'+member.id">
                                    </Modal>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button @click="logout" class="btn btn-success">Logout</button>
        </div>
    </div>
</template>
<style scoped>

img {
    object-fit: cover;
}

</style>
