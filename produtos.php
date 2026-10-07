<?php
session_start();

// 1. Incluir a conexão com o banco de dados (que já está configurada para PostgreSQL)
require_once __DIR__ . '/config/database.php';

// 2. Buscar os produtos do banco de dados PostgreSQL
try {
    $stmt = $pdo->query("SELECT * FROM produtos ORDER BY categoria, nome");
    $produtos = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erro ao buscar produtos: " . $e->getMessage());
}

// 3. Conta total de itens no carrinho para exibir na badge da navbar
$totalItensCarrinho = 0;
if (isset($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        $totalItensCarrinho += $item['quantidade'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLSHOP | Vitrine Dinâmica</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .product-card {
            background-color: #1e293b;
            border: 1px solid #334155;
            border-radius: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
            border-color: #3b82f6;
        }
        .product-img {
            height: 250px;
            object-fit: cover;
            width: 100%;
            background-color: #0f172a;
        }
        .price-tag {
            color: #10b981;
            font-size: 1.25rem;
            font-weight: 700;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-secondary fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center fw-bold" href="produtos.php">
            <img src="assets/img/logo_clshop.PNG" alt="Logo CLSHOP" height="35" class="me-2">
            CLSHOP
        </a>
        <div class="d-flex align-items-center">
            <a href="carrinho.php" class="btn btn-outline-primary position-relative me-3">
                <i class="bi bi-cart3"></i> Carrinho
                <?php if ($totalItensCarrinho > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?php echo $totalItensCarrinho; ?>
                    </span>
                <?php endif; ?>
            </a>
            <span class="text-light me-3 d-none d-md-inline">
                <i class="bi bi-person-circle me-1"></i> 
                <?php echo isset($_SESSION['usuario_nome']) ? htmlspecialchars($_SESSION['usuario_nome']) : 'Cliente'; ?>
            </span>
            <a href="logout.php" class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </div>
    </div>
</nav>

<div class="container my-5 pt-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold text-uppercase text-white">Destaques da Coleção</h1>
        <p class="text-muted">Confira as novidades exclusivas em vestuário e acessórios buscando dados do PostgreSQL.</p>
    </div>

    <div class="row g-4">
        <?php if (empty($produtos)): ?>
            <div class="col-12 text-center text-muted">
                <p>Nenhum produto cadastrado no momento.</p>
            </div>
        <?php else: ?>
            <!-- LOOP PHP QUE MONTA A VITRINE DINAMICAMENTE -->
            <?php foreach ($produtos as $produto): ?>
                <div class="col-md-4">
                    <div class="card product-card h-100">
                        <img src="<?php echo htmlspecialchars($produto['imagem']); ?>" class="card-img-top product-img" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <span class="badge bg-primary mb-2"><?php echo htmlspecialchars($produto['categoria']); ?></span>
                                <h5 class="card-title text-white fw-bold"><?php echo htmlspecialchars($produto['nome']); ?></h5>
                                <p class="card-text text-muted small"><?php echo htmlspecialchars($produto['descricao']); ?></p>
                            </div>
                            <div class="mt-3">
                                <div class="price-tag mb-3">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></div>
                                
                                <!-- O formulário agora envia apenas o ID do produto -->
                                <form action="carrinho.php" method="POST">
                                    <input type="hidden" name="acao" value="adicionar">
                                    <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
                                    <button type="submit" class="btn btn-clshop w-100"><i class="bi bi-cart-plus me-2"></i>Comprar Agora</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>