<?php
// Verificar se o usuário é admin
session_start();
if ($_SESSION['usuario_tipo'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Conexão ao banco de dados
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_POST['usuario_id'];
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    $descricao = $_POST['descricao'];

    // Inserir consulta no banco de dados
    $sql = "INSERT INTO consultas (usuario_id, data, hora, descricao) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$usuario_id, $data, $hora, $descricao])) {
        echo "Consulta criada com sucesso!";
        
        // Integração com Google Calendar
        // Aqui você precisará integrar com a API do Google Calendar para enviar o evento
    } else {
        echo "Erro ao criar consulta.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - Criar Consulta</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Criar Consulta</h2>
    <form action="painel_admin.php" method="POST">
        <label for="usuario_id">ID do Usuário:</label>
        <input type="number" name="usuario_id" required><br>
        
        <label for="data">Data:</label>
        <input type="date" name="data" required><br>
        
        <label for="hora">Hora:</label>
        <input type="time" name="hora" required><br>
        
        <label for="descricao">Descrição:</label>
        <input type="text" name="descricao" required><br>
        
        <button type="submit">Criar Consulta</button>
    </form>
</body>
</html>
