export const handleValidationError = (err, formRef, commonError)=>{
    if(err instanceof Error){
        commonError.value = err.message;
    }
    for (const field in err) {
        formRef.value.classList.remove('was-validated');
        formRef.value[field].classList.add('is-invalid');
        const errorField = document.getElementById(`${field}_error`);
        errorField.textContent=err[field].join(' \n');
    }
}
