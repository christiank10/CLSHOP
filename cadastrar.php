<?php
require_once __DIR__ . '/config/database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitização dos dados de entrada
    $nome  = trim($_POST['nome'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $cpf   = trim($_POST['cpf'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // Validação backend
    if (empty($nome) || empty($email) || empty($cpf) || empty($senha)) {
        die("Erro: Todos os campos são obrigatórios.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Erro: Formato de e-mail inválido.");
    }

    // Criptografia segura da senha (Bcrypt)
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    try {
        // Query tratada com Prepared Statement contra SQL Injection
        $sql = "INSERT INTO usuarios (nome, email, cpf, senha) VALUES (:nome, :email, :cpf, :senha)";
        $stmt = $pdo->prepare($sql);
        
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':senha', $senhaHash);

        if ($stmt->execute()) {
            echo "<div style='background-color:#0f172a; color:#22c55e; padding:20px; font-family:sans-serif; text-align:center;'>";
            echo "<h2>Cadastro realizado com sucesso na CLSHOP! 🛍️</h2>";
            echo "<p style='color:#f8fafc;'>Seja bem-vindo(a), " . htmlspecialchars($nome) . ".</p>";
            echo "<a href='index.html' style='color:#3b82f6;'>Voltar ao início</a>";
            echo "</div>";
        }
    } catch (PDOException $e) {
        // Código de erro de violação de chave única no PostgreSQL (23505)
        if ($e->getCode() == '23505') {
            echo "<div style='background-color:#0f172a; color:#ef4444; padding:20px; font-family:sans-serif; text-align:center;'>";
            echo "<h2>Erro ao cadastrar!</h2>";
            echo "<p style='color:#f8fafc;'>Este E-mail ou CPF já está cadastrado no sistema.</p>";
            echo "<a href='index.html' style='color:#3b82f6;'>Tentar novamente</a>";
            echo "</div>";
        } else {
            echo "Erro no sistema: " . $e->getMessage();
        }
    }
} else {
    header("Location: index.html");
    exit;
}
?>