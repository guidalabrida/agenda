<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica se o usuário está logado e se é um usuário normal
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] != 'usuario') {
    header("Location: login.php");
    exit();
}

// Exibe uma mensagem de boas-vindas ao usuário
echo "<h1>Bem-vindo, " . htmlspecialchars($_SESSION['usuario_nome']) . "!</h1>";
?>

<!-- Exemplo de funcionalidades para o usuário -->
<ul>
    <li><a href="minhas_consultas.php">Ver minhas consultas</a></li>
    <li><a href="logout.php">Sair</a></li>
</ul>
