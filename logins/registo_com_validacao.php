<!DOCTYPE html>
<html lang="pt-PT">
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE-edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../assets/css/registo_style.css">
        <title>BOOKhub - Registo</title>
    </head>
    <body>
        <div class="logo">
            <img src="../assets/img/Logotipo_Bookhub.png" alt="Logotipo" class="logo-img">
        </div>
        <div class="content">
            <h1>Registo📚</h1>
            <form id="form" action="../assets/php/registo_com_validacao.php" method="POST"> 
                <div>
                    <input type="text" placeholder="Nome Completo" class="inputs required" oninput="nameValidate()" name="nome_completo">
                    <span class="span-required">O nome deve ter no mínimo 5 caracteres</span>
                </div>
                <div>
                    <input type="email" placeholder="Email" class="inputs required" oninput="emailValidate()" name="email">
                    <span class="span-required">Digite um email válido</span>
                </div>
                <div style="position: relative;">
                    <input type="password" placeholder="Password" class="inputs required" oninput="mainPasswordValidate()" id="password" name="password">
                    <span class="span-required">A password deve ter no mínimo 8 caracteres</span>
                    <span class="password-toggle-icon">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                <div style="position: relative;">
                    <input type="password" placeholder="Confirme a sua password" class="inputs required" oninput="comparePassword()" id="confirmPassword" name="password">
                    <span class="span-required">As passwords devem ser iguais</span>
                    <span class="password-toggle-icon">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                <p class="genero"><b>Género:</b></p>
                <div class="box-select">
                    <div>
                        <input type="radio" id="m" value="m" name="genero">
                        <label for="m">Masculino</label>
                    </div>
                    <div>
                        <input type="radio" id="f" value="f" name="genero">
                        <label for="f">Feminino</label>
                    </div>
                    <div>
                        <input type="radio" id="o" value="o" name="genero">
                        <label for="o">Outro</label>
                    </div>
                </div>                
                <button><b>Enviar</b></button>
            </form>
        </div>
    </body>
    <script>
        const form = document.getElementById('form');
        const campos = document.querySelectorAll('.required');
        const spans = document.querySelectorAll('.span-required');
        const emailRegex = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
        const passwordFields = document.querySelectorAll('input[type="password"]');
        const toggleIcons = document.querySelectorAll('.password-toggle-icon i');

        form.addEventListener('submit', (event) => {
            event.preventDefault();
            nameValidate();
            emailValidate();
            mainPasswordValidate();
            comparePassword();
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
    </script>
</html>