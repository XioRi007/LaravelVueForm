<script setup>
import {onMounted, ref} from "vue";
import { vMaska } from "maska"
import {useStore} from "vuex";
import {useRouter} from "vue-router";
import {handleValidationError} from "@/handlers";
import {countries, phoneMaska} from "@/utils";

const props = defineProps({
    memberId:{
        type: Number,
        required: true,
    },
    member:{
        type: Object,
        required: true,
    },
    targetId:{
        type: String,
        required: true,
    }
});
const store = useStore();
const router = useRouter();
const commonError = ref(null);
const formRef = ref(null);
const form = ref( Object.assign({}, props.member));

onMounted(()=>{
    const modal = document.getElementById(props.targetId);
    modal.addEventListener('hidden.bs.modal', ()=>{
        reset();
    })
});
const handler = async (e) => {
    if (formRef.value.checkValidity()) {
        try {
            if (form.value.phone === "+0(000)-000-0000") {
                throw {
                    phone: ['Enter real phone number.']
                }
            }
            await store.dispatch('members/update', {...form.value, id: props.memberId});
            router.go();
        } catch (err) {
            console.log(err)
            handleValidationError(err, formRef, commonError);
        }
    } else {
        e.stopPropagation()
        formRef.value.classList.add('was-validated');
    }
}
const reset = () => {
    form.value = Object.assign({}, props.member);
}
const todayDate = () => {
    let today = new Date();
    let year = today.getFullYear();
    let month = String(today.getMonth() + 1).padStart(2, '0');
    let day = String(today.getDate()).padStart(2, '0');
    return year + '-' + month + '-' + day;
}
const setPhoto = (event) => {
    form.value.photo = event.target.files[0];
}
</script>

<template>
    <form ref="formRef" @submit.prevent="handler" @reset.prevent="reset" novalidate class="p-3">
        <div class="row">
            <div class="col-6">
                <div class="row mb-3">
                    <label  class="col-sm-3 col-form-label required" for="firstName">First Name</label>
                    <div class="col-sm-9">
                        <input id="firstName" name="firstName" class="form-control" type="text" maxlength="30" v-model="form.firstName" required/>
                        <div class="invalid-feedback" id="firstName_error">
                            Please enter a first name.
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label  class="col-sm-3 col-form-label required" for="last-name">Last Name</label>
                    <div class="col-sm-9">
                        <input id="lastName" name="lastName" class="form-control" type="text" maxlength="30" v-model="form.lastName" required />
                        <div class="invalid-feedback" id="lastName_error">
                            Please enter a last name.
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label  class="col-sm-3 col-form-label required" for="birthdate">Birthdate</label>
                    <div class="col-sm-9">
                        <input id="birthdate" name="birthdate" class="form-control" :max="todayDate()" type="date" v-model="form.birthdate" required />
                        <div class="invalid-feedback" id="birthdate_error">
                            Please enter a birthdate.
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label  class="col-sm-3 col-form-label required" for="report-subject">Report Subject</label>
                    <div class="col-sm-9">
                        <input id="reportSubject" name="reportSubject" class="form-control" type="text" maxlength="255" v-model="form.reportSubject" required />
                        <div class="form-text" id="basic-addon4">Maximum length is 255 symbols.</div>
                        <div class="invalid-feedback" id="reportSubject_error">
                            Please enter a report subject.
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label  class="col-sm-3 col-form-label required" for="country">Country</label>
                    <div class="col-sm-9">
                        <select class="form-select" name="country" aria-label="Default select example" v-model="form.country" >
                            <option v-for="country in countries" :key="country">{{ country }}</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label  class="col-sm-3 col-form-label required" for="phone">Phone</label>
                    <div class="col-sm-9">
                        <input id="phone" name="phone" class="form-control" pattern="\+[0-9]{1}\([0-9]{3}\)-[0-9]{3}-[0-9]{4}" v-maska :data-maska=phoneMaska v-model="form.phone" required>
                        <div class="invalid-feedback" id="phone_error">
                            Please enter a phone.
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label  class="col-sm-3 col-form-label required" for="email">Email</label>
                    <div class="col-sm-9">
                        <input id="email" name="email" class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" type="email" maxlength="50" v-model="form.email" required />
                        <div class="invalid-feedback" id="email_error">
                            Please enter a valid email.
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="row mb-3">
                    <label  class="col-sm-3 col-form-label" for="company">Company</label>
                    <div class="col-sm-9">
                        <input id="company" name="company" maxlength="50" class="form-control" type="text" v-model="form.company"/>
                        <div class="invalid-feedback" id="company_error">
                            Please enter a valid company.
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label  class="col-sm-3 col-form-label" for="position">Position</label>
                    <div class="col-sm-9">
                        <input id="position" name="position" maxlength="50" class="form-control" type="text" v-model="form.position"/>
                        <div class="invalid-feedback" id="position_error">
                            Please enter a valid position.
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label  class="col-sm-3 col-form-label" for="about">About</label>
                    <div class="col-sm-9">
                        <textarea id="about" name="about" class="form-control" rows="5" v-model="form.about"></textarea>
                        <div class="invalid-feedback" id="about_error">
                            Please enter a valid about.
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label  class="col-sm-3 col-form-label" for="photo">Photo</label>
                    <div class="col-sm-9">
                        <input id="photo" name="photo" class="form-control" type="file" @input="setPhoto">
                        <div class="invalid-feedback" id="photo_error">
                            Please enter a valid photo.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3 text-danger text-left text-center">
            <p>* - Required</p>
        </div>
        <div class="row mb-3 text-danger" v-if="commonError!=null">
            <p>{{ commonError }}</p>
        </div>
        <div class="controls justify-content-start">
            <button class="btn btn-success" type="submit">
                Save
            </button>
            <button class="btn btn-primary me-3" type="reset">
                Clear changes
            </button>
        </div>
    </form>
</template>
