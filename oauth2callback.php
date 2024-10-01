<?php
// Carregar as bibliotecas do Google API Client
require 'vendor/autoload.php';

session_start();

// Instanciar o cliente da API do Google
$client = new Google_Client();
$client->setAuthConfig('C:\xampp\htdocs\agenda\client_secret_1090369482239-jaa6n2ke87e3eisjmm1u3ibo6imkgah1.apps.googleusercontent.com.json'); // Atualize com o caminho correto do arquivo
$client->setRedirectUri('http://localhost/agenda/oauth2callback.php');
$client->addScope(Google_Service_Calendar::CALENDAR);
$client->setAccessType('offline');

// Verifique se recebemos o código de autorização na URL
if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Tentar trocar o código de autorização por um token de acesso
    $token = $client->fetchAccessTokenWithAuthCode($code);

    // Verificar se o token foi obtido corretamente
    if (isset($token['error'])) {
        // Exibir o erro retornado pelo Google
        echo "Erro ao obter o token: " . htmlspecialchars($token['error']);
        exit();
    }

    // Salvar o token na sessão se estiver correto
    if (isset($token['access_token'])) {
        $_SESSION['access_token'] = $token['access_token'];
        // Redirecionar para a página principal
        header('Location: create_event.php');
        exit();
    } else {
        // Caso o token não tenha sido obtido, exiba a resposta para depuração
        echo "Erro desconhecido ao obter o token. Resposta:";
        var_dump($token);
    }

} else {
    echo "Código de autorização não encontrado!";
}
