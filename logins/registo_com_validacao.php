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
        <?php
            session_start();
            $erro_email = $_SESSION['erro_email'] ?? '';
            unset($_SESSION['erro_email']);
        ?>

        <div class="logo">
            <img src="../assets/img/Logotipo_Bookhub.png" alt="Logotipo" class="logo-img">
        </div>

        <div class="content">
            <h1>Registo📚</h1>

            <!-- div para mostrar erros -->
            <?php if ($erro_email): ?>
                <div class="error-message" styke="color: red; margin-bottom: 15px;">
                    <?= htmlspecialchars($erro_email) ?>
                </div>
            <?php endif; ?>

            <form id="form" action="../assets/php/captar_registo.php" method="POST"> 
                <div>
                    <input type="text" placeholder="Nome Completo" class="inputs required" oninput="nameValidate()" name="nome_completo">
                    <span class="span-required">O nome deve ter no mínimo 5 caracteres</span>
                </div>
                <div>
                    <input type="email" placeholder="Email" class="inputs required" oninput="emailValidate()" name="email">
                    <span class="span-required">Digite um email válido</span>
                    <!-- Mensagem de erro específica será exibida aqui -->
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
                <button class="btn"><b>Enviar</b></button>
            </form>
        </div>
        <script src="../assets/js/registo_com_validacao.js"></script>
    </body>
</html>