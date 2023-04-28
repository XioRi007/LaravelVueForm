<script setup>
    import { useStore } from 'vuex'
    import { vMaska } from "maska"
    import { onMounted, ref } from 'vue';
    import {handleValidationError} from "@/handlers";
    import {countries, phoneMaska} from "@/utils";
    import Loader from "@/components/Loader.vue";

    const props = defineProps(['onNext']);
    const store = useStore();
    const loading = ref(true);
    const formRef = ref(null);
    const commonError = ref(null);
    const personalForm = ref(store.getters['members/getPersonal']);

    /**
     * If store contains member's id and the fields are empty
     * Loads information
     */
    onMounted(async ()=>{
        const id = store.getters['members/getId'];
        if(id && !personalForm.firstName){
            try{
                await store.dispatch('members/loadMember', ['firstName', 'lastName', 'reportSubject', 'email', 'phone', 'country', 'birthdate']);
                personalForm.value = store.getters['members/getPersonal'];
            }
            catch(err){
                commonError.value = err.message
            }
        }
        loading.value = false;
    });

    /**
     * Input handler
     */
    const setPersonal = (e) =>{
        e.target.classList.remove('is-invalid');
        store.commit('members/setForm', {
            name:e.target.name,
            value:e.target.value
        });
    }
    /**
     * If localStorage contains id - updating
     * Else creating register
     */
    const nextClick = async(e) => {
        e.preventDefault();
        if (formRef.value.checkValidity()) {
            try{
                if(personalForm.value.phone === "+0(000)-000-0000"){
                    throw {
                        phone:['Enter real phone number.']
                    }
                }
                const userId = store.getters['members/getId'];
                if(userId != null){
                    await store.dispatch('members/update', {...personalForm.value, id:userId});
                }else{
                    await store.dispatch('members/registerParticipant');
                    const user = store.getters['members/getId'];
                    localStorage.setItem('user', user);
                }
                props.onNext();
            }catch(err){
                handleValidationError(err, formRef, commonError);
            }
        }else{
            e.stopPropagation()
            formRef.value.classList.add('was-validated');
        }

    }
    const todayDate = () => {
        let today = new Date();
        let year = today.getFullYear();
        let month = String(today.getMonth() + 1).padStart(2, '0');
        let day = String(today.getDate()).padStart(2, '0');
        return year + '-' + month + '-' + day;
    }

</script>

<template>
    <Loader v-if="loading"/>
    <form v-else @submit.prevent="nextClick" ref="formRef" novalidate>
    <div class="row mb-3">
        <label  class="col-sm-3 col-form-label required" for="firstName">First Name</label>
        <div class="col-sm-9">
            <input id="firstName" name="firstName" class="form-control" type="text" maxlength="30" v-model="personalForm.firstName" @input="setPersonal" required/>
            <div class="invalid-feedback" id="firstName_error">
                Please enter a first name.
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label  class="col-sm-3 col-form-label required" for="last-name">Last Name</label>
        <div class="col-sm-9">
            <input id="lastName" name="lastName" class="form-control" type="text" maxlength="30" v-model="personalForm.lastName" @input="setPersonal" required />
            <div class="invalid-feedback" id="lastName_error">
                Please enter a last name.
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label  class="col-sm-3 col-form-label required" for="birthdate">Birthdate</label>
        <div class="col-sm-9">
            <input id="birthdate" name="birthdate" class="form-control" :max="todayDate()" type="date" v-model="personalForm.birthdate" @input="setPersonal" required />
            <div class="invalid-feedback" id="birthdate_error">
                Please enter a birthdate.
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label  class="col-sm-3 col-form-label required" for="report-subject">Report Subject</label>
        <div class="col-sm-9">
            <input id="reportSubject" name="reportSubject" class="form-control" type="text" maxlength="255" v-model="personalForm.reportSubject" @input="setPersonal" required />
            <div class="form-text" id="basic-addon4">Maximum length is 255 symbols.</div>
            <div class="invalid-feedback" id="reportSubject_error">
                Please enter a report subject.
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label  class="col-sm-3 col-form-label required" for="country">Country</label>
        <div class="col-sm-9">
            <select class="form-select" name="country" aria-label="Default select example" v-model="personalForm.country" @input="setPersonal" >
                <option v-for="country in countries" :key="country">{{ country }}</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <label  class="col-sm-3 col-form-label required" for="phone">Phone</label>
        <div class="col-sm-9">
            <input id="phone" name="phone" class="form-control" pattern="\+[0-9]{1}\([0-9]{3}\)-[0-9]{3}-[0-9]{4}" v-maska :data-maska=phoneMaska v-model="personalForm.phone" @input="setPersonal" required>
            <div class="invalid-feedback" id="phone_error">
                Please enter a phone.
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label  class="col-sm-3 col-form-label required" for="email">Email</label>
        <div class="col-sm-9">
            <input id="email" name="email" class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" type="email" maxlength="50" v-model="personalForm.email" @input="setPersonal" required />
            <div class="invalid-feedback" id="email_error">
                Please enter a valid email.
            </div>
        </div>
    </div>
    <div class="row mb-3 text-danger text-left">
        <p>* - Required</p>
    </div>
    <div class="row mb-3 text-danger" v-if="commonError!=null">
        <p>{{ commonError }}</p>
    </div>
    <div class="controls">
        <button class="btn btn-success" type="submit">
            Next
        </button>
    </div>
</form>
</template>



<style>
.required:after {
  content: "*";
  color: red;
  margin-left: 5px;
}

</style>
