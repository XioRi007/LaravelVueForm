<script setup>
    import { useStore } from 'vuex'
    import { vMaska } from "maska"
    import { onMounted, ref } from 'vue';
    import {handleValidationError} from "@/handlers";
    import {countries, phoneMaska} from "@/utils";
    import Loader from "@/components/Loader.vue";
    import ShareLinks from "@/components/ShareLinks.vue";

    const props = defineProps(['onNext']);
    const store = useStore();
    const loading = ref(true);
    const formRef = ref(null);
    const membersCount = ref(0);

    /**
     * If store contains member's id and the fields are empty
     * Loads information
     */
    onMounted(async ()=>{
        await store.dispatch('members/fetchMembersCount');
        membersCount.value = store.getters['members/getMembersCount'];
        loading.value = false;
    });

</script>

<template>
    <Loader v-if="loading"/>
    <h1 v-else class="my-5">You have successfully registered!</h1>
    <div class="row justify-content-center mb-5 bg-body">
        <div class="col-9 justify-center">
            <h4 class="">Share our conference with others:</h4>
            <ShareLinks></ShareLinks>
            <div class="row mt-5">
                <RouterLink to="/members" class="col text-center" >All members({{ membersCount }})</RouterLink>
            </div>

        </div>
    </div>
</template>



<style>
.required:after {
  content: "*";
  color: red;
  margin-left: 5px;
}

</style>
