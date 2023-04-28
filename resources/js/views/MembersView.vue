<script setup>
  import { onBeforeMount, ref } from 'vue';
  import { useStore } from 'vuex';
  import Loader from "@/components/Loader.vue";

  const store = useStore();
  const members = ref([]);
  const img = ref('');
  const loading = ref(true);

  onBeforeMount(async() => {
    const fields = ['firstName', 'lastName', 'photo', 'reportSubject', 'email'];
    await store.dispatch('members/fetchMembers', {fields});
    members.value = store.getters['members/getMembers'];
    loading.value = false;
  });

</script>

<template>
  <div class="container my-5 border shadow p-5 pt-0">
    <h1 class="text-center my-5">All members</h1>
      <Loader v-if="loading"/>
        <div v-else class="table-responsive">
            <table class="table align-middle ">
                <thead>
                  <tr>
                    <th scope="col">Photo</th>
                    <th scope="col">Full Name</th>
                    <th scope="col">Report Subject</th>
                    <th scope="col">Email</th>
                  </tr>
                </thead>
                <tbody class="table-group-divider ">
                  <tr v-for="member in members" :key="member.id">
                    <td><img alt="members image" :src="member.photo" class="rounded-circle" width="50" height="50"></td>
                    <td>{{ member.firstName+" "+ member.lastName}}</td>
                    <td>{{ member.reportSubject }}</td>
                    <td><a href="{{ member.email }}">{{ member.email }}</a></td>
                  </tr>
                </tbody>
            </table>
        </div>
  </div>

</template>



<style scoped>
img {
   object-fit: cover;
}
</style>

