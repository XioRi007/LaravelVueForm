<script setup>
import {ref} from "vue";

const props = defineProps({
    targetId: {
        type: String,
        required: true,
    },
    title:{
        type: String,
        required: true,
    },
    text:{
        type: String,
        required: false,
    },
    handler:{
        type: Function,
        required: false,
    },
    icon: {
        type: Object,
        required: true
    },
    button: {
        type: Boolean,
        required: false,
        default: true
    },
});

const commonError = ref(null);

const handleHandler = async () => {
    try{
        await props.handler();
    }catch (err){
        commonError.value = err.message;
    }
}

</script>
<template>
    <button class="btn btn-light mb-2" data-bs-toggle="modal" :data-bs-target="'#'+targetId" :title="title"> <component :is="icon"/> </button>
    <div class="modal fade" :id="targetId" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog width">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalLabel">{{title}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{text}}
                    <slot/>
                    <div class="row mb-3 text-danger text-center" v-if="commonError!=null">
                        <p>{{ commonError }}</p>
                    </div>
                </div>
                <div v-if="button" class="modal-footer">
                    <button type="button" class="btn btn-success" @click="handleHandler">Save</button>
                </div>
            </div>
        </div>
    </div>

</template>
<style>
.width{
    max-width: fit-content!important;
    min-width: 25%;
}
</style>
