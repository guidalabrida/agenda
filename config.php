<?php
$host = 'localhost';               // Servidor
$db = 'sistema_consultas';     // Nome do banco de dados
$user = 'root';                    // Usuário do banco de dados
$pass = '';                        // Senha (geralmente vazia no XAMPP)

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4"; // Data Source Name
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Modo de erro
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Modo de busca padrão
    PDO::ATTR_EMULATE_PREPARES => false,           // Emular prepares
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options); // Estabelece a conexão
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode()); // Tratamento de erros
}
?>
