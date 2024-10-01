<?php
require 'vendor/autoload.php';
session_start();

// Verifique se o token de acesso está na sessão
if (!isset($_SESSION['access_token']) || !$_SESSION['access_token']) {
    header('Location: login.php');
    exit();
}

// Instanciar o cliente da API do Google
$client = new Google_Client();
$client->setAuthConfig('C:/xampp/htdocs/agenda/client_secret_1090369482239-jaa6n2ke87e3eisjmm1u3ibo6imkgah1.apps.googleusercontent.com.json');
$client->setAccessToken($_SESSION['access_token']);

// Verifica se o token expirou e tenta renová-lo
if ($client->isAccessTokenExpired()) {
    // Verifica se o refresh token está disponível
    if (isset($_SESSION['access_token']['refresh_token'])) {
        $client->fetchAccessTokenWithRefreshToken($_SESSION['access_token']['refresh_token']);
        $_SESSION['access_token'] = $client->getAccessToken(); // Atualiza o token na sessão
    } else {
        // Se não houver refresh token, redirecione para o login novamente
        header('Location: login.php');
        exit();
    }
}

// Instancia o serviço do Google Calendar
$service = new Google_Service_Calendar($client);

// Criar um novo evento
$event = new Google_Service_Calendar_Event(array(
  'summary' => 'Consulta Médica',
  'location' => 'Clínica ABC',
  'description' => 'Consulta com o Dr. João.',
  'start' => array(
    'dateTime' => '2024-09-25T09:00:00-03:00', // Defina a data e hora da consulta
    'timeZone' => 'America/Sao_Paulo',
  ),
  'end' => array(
    'dateTime' => '2024-09-25T10:00:00-03:00', // Defina o horário de término da consulta
    'timeZone' => 'America/Sao_Paulo',
  ),
  'attendees' => array(
    array('email' => 'paciente@gmail.com'), // Email do paciente
  ),
  'reminders' => array(
    'useDefault' => false,
    'overrides' => array(
      array('method' => 'email', 'minutes' => 24 * 60), // Lembrar por email 1 dia antes
      array('method' => 'popup', 'minutes' => 10), // Lembrar por popup 10 minutos antes
    ),
  ),
));

$calendarId = 'primary'; // Utiliza o calendário principal

// Tenta inserir o evento no calendário
try {
    $event = $service->events->insert($calendarId, $event);
    echo 'Evento criado com sucesso: ' . $event->htmlLink;
} catch (Exception $e) {
    echo 'Erro ao criar evento: ' . $e->getMessage();
}
?>
