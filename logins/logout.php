<?php
session_start(); // Inicia a sessão

// Verifica se a sessão está ativa
if (isset($_SESSION["loggedin"])) {
    // Destrói todas as variáveis de sessão
    $_SESSION = array();

    // Se você deseja destruir a sessão completamente, também deve destruir o cookie da sessão.
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Por fim, destrói a sessão
    session_destroy();
}

// Redireciona para a página inicial ou página de login
header("Location: ../index.php");
exit; // Certifique-se de sair após o redirecionamento