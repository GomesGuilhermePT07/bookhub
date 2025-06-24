const form = document.getElementById('form');
const campos = document.querySelectorAll('.required');
const spans = document.querySelectorAll('.span-required');
const emailRegex = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
const passwordFields = document.querySelectorAll('input[type="password"]');
const toggleIcons = document.querySelectorAll('.password-toggle-icon i');

let formIsValid = true;

form.addEventListener("submit", (event) => {
    // Reseta o estado de validação
    formIsValid = true;

    // Executa todas as validações
    nameValidate();
    emailValidate();
    mainPasswordValidate();
    comparePassword();

    // Se algum campo for invalido, provine o envio
    if (!formIsValid) {
        event.preventDefault();
        // alert("Por favor, corrija os campos destacados em vermelho!");
    }
});

toggleIcons.forEach((icon, index) => {
    icon.addEventListener('click', function () {
        if (passwordFields[index].type === "password") {
            passwordFields[index].type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            passwordFields[index].type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    });
});

function setError(index){
    campos[index].style.border = '2px solid #e63636';
    spans[index].style.display = 'block';
    formIsValid = false; // Marca o formulário como inválido
}

function removeError(index){
    campos[index].style.border = '';
    spans[index].style.display = 'none';
}

function nameValidate(){
    if(campos[0].value.length < 5) {
        setError(0);
    } else {
        removeError(0);
    }
}

function emailValidate(){
    if(!emailRegex.test(campos[1].value)) {
        setError(1);
    } else {
        removeError(1);
    }
}

function mainPasswordValidate(){
    if(campos[2].value.length < 8) {
        setError(2);
    } else {
        removeError(2);
        comparePassword();
    }
}

function comparePassword(){
    if(campos[2].value === campos[3].value && campos[3].value.length >= 8) {
        removeError(3);
    } else {
        setError(3);
    }
}