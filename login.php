<?php
session_start();
require 'config.php'; // Arquivo de configuração do banco de dados
require 'vendor/autoload.php'; // Autoload das bibliotecas do Google

// Instanciar o cliente da API do Google
$client = new Google_Client();
$client->setAuthConfig('C:\xampp\htdocs\agenda\client_secret_1090369482239-jaa6n2ke87e3eisjmm1u3ibo6imkgah1.apps.googleusercontent.com.json');
$client->setRedirectUri('http://localhost/agenda/oauth2callback.php');
$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
$client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se os campos de email e senha foram preenchidos
    if (!empty($_POST['email']) && !empty($_POST['senha'])) {
        $email = trim($_POST['email']);
        $senha = trim($_POST['senha']);

        // Verifica as credenciais no banco de dados
        $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch();

            if (password_verify($senha, $user['senha'])) {
                // Define as informações do usuário na sessão
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['usuario_nome'] = $user['nome'];
                $_SESSION['usuario_email'] = $user['email'];
                $_SESSION['usuario_tipo'] = $user['tipo'];

                if ($user['tipo'] == 'admin') {
                    header("Location: painel_admin.php");
                } else {
                    header("Location: painel.php");
                }
                exit();
            } else {
                echo "<p style='color: red;'>Senha incorreta.</p>";
            }
        } else {
            echo "<p style='color: red;'>Usuário não encontrado.</p>";
        }
    }
}

// Verifica se o usuário está autenticado via Google OAuth
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
    
    // Pegar informações do usuário, se necessário
    $google_oauth = new Google_Service_Oauth2($client);
    $user_info = $google_oauth->userinfo->get();

    $_SESSION['usuario_nome'] = $user_info->name;
    $_SESSION['usuario_email'] = $user_info->email;

    // Aqui, você pode verificar se o usuário existe no seu banco de dados
    $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $user_info->email]);

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch();
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario_tipo'] = $user['tipo'];

        if ($user['tipo'] == 'admin') {
            header("Location: painel_admin.php");
        } else {
            header("Location: painel.php");
        }
        exit();
    } else {
        // Se o usuário não existe, você pode querer cadastrá-lo ou mostrar uma mensagem
        echo "<p style='color: red;'>Usuário não cadastrado. Faça login com as credenciais do sistema.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gerenciador de Consultas Médicas</title>
    <link rel="stylesheet" href="style.css"> <!-- Inclua seu CSS aqui -->
</head>
<body>
    <h2>Login</h2>
    <form action="login.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Digite seu email" required>
        <br><br>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" placeholder="Digite sua senha" required>
        <br><br>

        <button type="submit">Entrar</button>
    </form>

    <p><a href="<?php echo $client->createAuthUrl(); ?>">Login com Google</a></p>
</body>
</html>
