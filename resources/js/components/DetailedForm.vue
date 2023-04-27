<script setup>
    import { useStore } from 'vuex'
    import {computed, onMounted, ref } from 'vue';
    import {handleValidationError} from "@/handlers";

    const props = defineProps(['onNext', 'onBack']);
    const store = useStore();
    const commonError = ref('');
    const detailedForm = ref({});
    const id = computed(()=>store.getters['members/getId'])
    const formRef = ref(null);

    /**
     * Input handler
     */
    const setDetailed = (e) =>{
        store.commit('members/setForm', {
            name:e.target.name,
            value:e.target.value
        })
    }

    /**
     * If store contains member's id
     * Loads details
     */
    onMounted(async ()=>{
        try{
            if(id.value != null){
                await store.dispatch('members/loadMember', ['about', 'company', 'position']);
                detailedForm.value = store.getters['members/getDetailed'];
            }
        }
        catch(err){
            console.log(err)
            commonError.value = err.message;
        }
    });

    /**
     * Updating member
     *
     */
    const submit = async() => {
        const user = store.getters['members/getId'];
        if(user != null){
            await store.dispatch('members/update', {...detailedForm.value, id:id.value});
        }
    }
    const backClick = async (e) => {
        try{
            e.preventDefault();
            await submit();
            props.onBack();
        }catch (err){
            console.log(err)
            handleValidationError(err, formRef, commonError);
        }
    }

    const nextClick = async (e) => {
        try{
            e.preventDefault();
            await submit();
            props.onNext();
        }catch (err){
            console.log(err)
            handleValidationError(err, formRef, commonError);
        }
    }
    const setPhoto = (event) => {
        store.commit('members/setForm', {
            name:'photo',
            value:event.target.files[0]
        })
    }

</script>

<template>
    <form ref="formRef">
    <div class="row mb-3">
        <label  class="col-sm-3 col-form-label" for="company">Company</label>
        <div class="col-sm-9">
            <input id="company" name="company" maxlength="50" class="form-control" type="text" v-model="detailedForm.company" @input="setDetailed"/>
            <div class="invalid-feedback" id="company_error">
                Please enter a valid company.
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label  class="col-sm-3 col-form-label" for="position">Position</label>
        <div class="col-sm-9">
            <input id="position" name="position" maxlength="50" class="form-control" type="text" v-model="detailedForm.position" @input="setDetailed"/>
            <div class="invalid-feedback" id="position_error">
                Please enter a valid position.
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label  class="col-sm-3 col-form-label" for="about">About</label>
        <div class="col-sm-9">
            <textarea id="about" name="about" class="form-control" rows="5" v-model="detailedForm.about" @input="setDetailed"></textarea>
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
    <div class="row mb-3 text-danger text-left">
        <p>* - Required</p>
    </div>
    <div class="row mb-3 text-danger" v-if="commonError !== ''">
        <p>{{ commonError }}</p>
    </div>
    <div class="controls">
        <button class="btn btn-success" @click="nextClick" type="submit">
            Complete
        </button>
        <button class="btn btn-outline-success" @click="backClick" type="submit">
            Back
        </button>
    </div>
</form>
</template>


<style>
</style>
