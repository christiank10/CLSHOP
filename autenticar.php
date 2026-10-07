<?php
session_start();
require_once __DIR__ . '/config/database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha)) {
        die("Por favor, preencha o e-mail e a palavra-passe.");
    }

    try {
        // Procura o utilizador pelo E-mail no banco PostgreSQL
        $sql = "SELECT id, nome, senha FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se o utilizador existe e se a palavra-passe criptografada bate certo
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id']   = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];

            // Redireciona diretamente para a vitrine de produtos
            header("Location: produtos.php");
            exit;
        } else {
            echo "<!DOCTYPE html>
            <html lang='pt-BR'>
            <head>
                <meta charset='UTF-8'>
                <title>Erro de Acesso | CLSHOP</title>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
                <link rel='stylesheet' href='assets/css/style.css'>
            </head>
            <body>
                <div class='container text-center py-5'>
                    <div class='card card-register p-5 mx-auto' style='max-width: 500px;'>
                        <h2 class='text-danger fw-bold mb-3'>Falha no Login</h2>
                        <p class='text-white'>E-mail ou palavra-passe incorretos.</p>
                        <a href='login.html' class='btn btn-clshop mt-3'>Tentar Novamente</a>
                    </div>
                </div>
            </body>
            </html>";
        }
    } catch (PDOException $e) {
        echo "Erro na autenticação: " . $e->getMessage();
    }
} else {
    header("Location: login.html");
    exit;
}
?>