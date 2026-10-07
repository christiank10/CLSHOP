<?php
session_start();
$_SESSION['carrinho'] = [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLSHOP | Compra Finalizada</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container text-center py-5">
    <div class="card card-register p-5 mx-auto" style="max-width: 550px;">
        <i class="bi bi-check-circle-fill text-success display-1 mb-3"></i>
        <h2 class="text-white fw-bold mb-3">Pedido Realizado com Sucesso! 🎉</h2>
        <p class="text-muted">Agradecemos a sua compra na <strong>CLSHOP</strong>. Enviamos o comprovante e o código de rastreio para o seu e-mail cadastrado.</p>
        
        <div class="mt-4">
            <a href="produtos.php" class="btn btn-clshop"><i class="bi bi-bag-plus me-2"></i>Fazer Nova Compra</a>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>