<?php
$host = 'localhost';
$db = 'sistema_consultas';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Consultas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Minhas Consultas</h2>
    <table border="1">
        <tr>
            <th>Data</th>
            <th>Hora</th>
            <th>Descrição</th>
        </tr>
        <?php
        session_start();
        $usuario_id = $_SESSION['usuario_id'];
        
        // Conexão ao banco de dados
        require 'config.php';
        
        $sql = "SELECT * FROM consultas WHERE usuario_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$usuario_id]);
        $consultas = $stmt->fetchAll();
        
        foreach ($consultas as $consulta) {
            echo "<tr>
                    <td>{$consulta['data']}</td>
                    <td>{$consulta['hora']}</td>
                    <td>{$consulta['descricao']}</td>
                  </tr>";
        }
        ?>
    </table>
</body>
</html>
