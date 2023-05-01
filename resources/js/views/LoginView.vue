<script setup>
import {onBeforeMount, ref} from "vue";
import {useRouter} from "vue-router";
import {useStore} from "vuex";

const store = useStore();
const router = useRouter();
const error = ref('');
const formRef = ref(null);
const form = ref({
    email: '',
    password: '',
});

onBeforeMount(()=>{
    if(store.getters["auth/getToken"] !== ""){
        router.push('/admin');
    }
})

const submit = async(e) => {
    try{
        console.log(formRef.value.checkValidity())
        if(formRef.value.checkValidity()){
            await store.dispatch('auth/login', form.value);
            window.location.href = '/admin';//not router.push to rerender the header link
        }else{
            e.stopPropagation()
            formRef.value.classList.add('was-validated');
        }
    }catch (e) {
        error.value = e.response.data.error;
    }
};
</script>

<template>
        <div class="container pt-5">
            <div class="row justify-content-center pt-5">
                <div class="col-md-6 pt-5">
                    <div class="card border border-success shadow-lg mt-5">
                        <div class="card-header fw-semibold text-center text-success border-success bg-white">Login</div>
                        <div class="card-body">
                            <form @submit.prevent="submit" ref="formRef" novalidate>
                                <div class="row mb-3">
                                    <label  class="col-sm-3 col-form-label required" for="email">Email</label>
                                    <div class="col-sm-9">
                                        <input id="email" name="email" class="form-control" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" maxlength="255" v-model="form.email" required/>
                                        <div class="invalid-feedback text-center">
                                            Please enter an email.
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label  class="col-sm-3 col-form-label required" for="password">Password</label>
                                    <div class="col-sm-9">
                                        <input id="password" minlength="8" aria-describedby="passwordHelp" name="password" class="form-control" type="password" maxlength="255" v-model="form.password" required/>
                                        <div class="invalid-feedback text-center">
                                            Please enter a password.
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-1 text-danger text-center">
                                    <p>* - Required</p>
                                </div>
                                <div class="row mb-3 text-danger text-center" v-if="error!=null">
                                    <p>{{ error }}</p>
                                </div>
                                <div class="row justify-content-end">
                                    <div class="col-3 col-xl-2">
                                        <button type="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template>
