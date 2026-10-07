<?php
// Configurações de conexão com o PostgreSQL
$host     = 'localhost';
$port     = '5432'; // Porta padrão do PostgreSQL
$dbname   = 'clshop_db';
$user     = 'postgres'; // Seu usuário do pgAdmin
$password = '@070724@'; // ⚠️ Altere para a senha que você definiu na instalação do PostgreSQL

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE                  => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE       => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES         => false,
    ]);
} catch (PDOException $e) {
    die("Erro na conexão com o PostgreSQL: " . $e->getMessage());
}
?>