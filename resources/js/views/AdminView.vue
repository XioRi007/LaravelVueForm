<script setup>
import {onBeforeMount} from "vue";
import {useRouter} from "vue-router";
import {useStore} from "vuex";
const router = useRouter();
const store = useStore();
onBeforeMount(async()=>{
    try{
        if(store.getters["auth/getToken"] === ''){
            await router.push('/login');
        }
    }catch (err){
        console.log(err);
        await router.push('/login');
    }
})
const logout = async () => {
    try {
        await store.dispatch('auth/logout');
        await router.push('/');
    } catch (err) {
        console.log(err);
        store.commit('auth/deleteToken');
        await router.push('/login');
    }
}
</script>

<template>
    <p>Login</p>
    <button @click="logout">Logout</button>
</template>
