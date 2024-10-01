<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require 'config.php'; // Inclui o arquivo de configuração do banco de dados

// Busca as consultas do usuário logado
$sql = "SELECT * FROM consultas WHERE usuario_id = :usuario_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['usuario_id' => $_SESSION['usuario_id']]);
$consultas = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Consultas - Gerenciador de Consultas Médicas</title>
    <link rel="stylesheet" href="style.css"> <!-- Inclua seu CSS aqui -->
</head>
<body>
    <h1>Minhas Consultas</h1>

    <?php if (count($consultas) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($consultas as $consulta): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($consulta['id']); ?></td>
                        <td><?php echo htmlspecialchars($consulta['data']); ?></td>
                        <td><?php echo htmlspecialchars($consulta['hora']); ?></td>
                        <td><?php echo htmlspecialchars($consulta['descricao']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Você não tem consultas agendadas.</p>
    <?php endif; ?>

    <a href="painel.php">Voltar ao painel</a>
</body>
</html>
