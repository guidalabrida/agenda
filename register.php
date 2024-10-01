<?php
// Iniciar a sessão
session_start();

// Incluir o autoload do Google API Client
require 'vendor/autoload.php';

// Instanciar o cliente da API do Google
// Instanciar o cliente da API do Google
$client = new Google_Client();

// Carregar o arquivo de configuração OAuth 2.0 (verifique o caminho correto para o arquivo JSON)
$client->setAuthConfig('C:/xampp/htdocs/agenda/client_secret_1090369482239-jaa6n2ke87e3eisjmm1u3ibo6imkgah1.apps.googleusercontent.com.json');

// Definir o URI de redirecionamento, deve ser o mesmo configurado no Google Developer Console
$client->setRedirectUri('http://localhost/agenda/oauth2callback.php');

// Adicionar o escopo necessário para o Google Calendar
$client->addScope(Google_Service_Calendar::CALENDAR);

// Permitir que o cliente obtenha tokens de atualização para acesso contínuo ("offline")
$client->setAccessType('offline');

// Definir para sempre solicitar consentimento ao usuário (opcional, mas útil para desenvolvimento)
$client->setPrompt('consent');


// Verificar se o código de autorização foi retornado
if (isset($_GET['code'])) {
    // Trocar o código de autorização pelo token de acesso
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['access_token'] = $token; // Salvar o token na sessão
    $client->setAccessToken($token);

    // Pegar informações do usuário via Google OAuth
    $google_oauth = new Google_Service_Oauth2($client);
    $user_info = $google_oauth->userinfo->get();

    // Armazenar as informações necessárias na sessão
    $_SESSION['usuario_nome'] = $user_info->name;
    $_SESSION['usuario_email'] = $user_info->email;

    // Verificar se o usuário já está registrado no banco de dados
    require 'config.php'; // Arquivo de configuração do banco de dados
    $email = $_SESSION['usuario_email'];

    $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);

    if ($stmt->rowCount() > 0) {
        // Usuário já está registrado, redirecionar para o painel de usuário
        header("Location: painel.php");
    } else {
        // Registrar o usuário no banco de dados
        $nome = $_SESSION['usuario_nome'];
        $tipo = 'user'; // Definir o tipo de usuário como padrão (user)

        $sql = "INSERT INTO usuarios (nome, email, tipo) VALUES (:nome, :email, :tipo)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['nome' => $nome, 'email' => $email, 'tipo' => $tipo]);

        // Redirecionar para o painel de usuário
        header("Location: painel.php");
    }
    exit();
}

// Se não houver token, redirecionar para o login do Google
if (!isset($_SESSION['access_token'])) {
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
    exit();
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Cadastro de Usuário</h2>
    <form action="register.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" required><br>
        
        <label for="email">Email:</label>
        <input type="email" name="email" required><br>
        
        <label for="senha">Senha:</label>
        <input type="password" name="senha" required><br>
        
        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>
