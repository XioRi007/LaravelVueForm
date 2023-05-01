  <template>
    <header class="navbar navbar-expand-lg navbar-light bg-light border border-success">
      <div class="container flex-nowrap">
        <router-link class="navbar-brand" to="/">Conference</router-link>
        <div class="collapse navbar-collapse d-flex justify-content-end" id="navbarNav">
          <ul class="navbar-nav ml-auto d-flex flex-row col-6 col-sm-4 col-lg-3 justify-content-between">
            <li class="nav-item">
              <router-link class="nav-link" to="/">Register</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/members">Members</router-link>
            </li>
            <li class="nav-item" v-if="isAdmin">
              <router-link class="nav-link text-success" to="/admin">Admin</router-link>
            </li>
            <li class="nav-item" v-if="isAdmin">
               <button class="btn btn-outline-danger ms-1" @click="logout">Logout</button>
            </li>
            <li class="nav-item" v-else>
              <router-link class="nav-link text-success" to="/login">Login</router-link>
            </li>
          </ul>
        </div>
      </div>
    </header>
  </template>
  <script setup>
  import { RouterLink } from 'vue-router'
  import {useStore} from "vuex";
  import {computed} from "vue";
  const store = useStore();
  const isAdmin = computed(()=>store.getters["auth/getToken"]);
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
  </script>
