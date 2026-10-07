<?php
session_start();

// 1. Inclui a conexão com o banco de dados PostgreSQL
require_once __DIR__ . '/config/database.php';

// 2. Inicializa a estrutura do carrinho na sessão se ainda não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// ----------------------------------------------------
// AÇÃO 1: Adicionar Produto ao Carrinho (Buscando do Banco)
// ----------------------------------------------------
if (isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {
    $idProduto = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($idProduto) {
        try {
            // Consulta o produto no banco para pegar preço e dados reais e seguros
            $sql = "SELECT id, nome, preco, imagem FROM produtos WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $idProduto, PDO::PARAM_INT);
            $stmt->execute();
            $produtoInfo = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($produtoInfo) {
                // Se já estiver no carrinho, apenas incrementa a quantidade
                if (isset($_SESSION['carrinho'][$idProduto])) {
                    $_SESSION['carrinho'][$idProduto]['quantidade'] += 1;
                } else {
                    // Adiciona o novo item
                    $_SESSION['carrinho'][$idProduto] = [
                        'nome'       => $produtoInfo['nome'],
                        'preco'      => (float)$produtoInfo['preco'],
                        'imagem'     => $produtoInfo['imagem'],
                        'quantidade' => 1
                    ];
                }
            }
        } catch (PDOException $e) {
            die("Erro ao carregar produto: " . $e->getMessage());
        }
    }
    header("Location: carrinho.php");
    exit;
}

// ----------------------------------------------------
// AÇÃO 2: Atualizar Quantidades (+ e -)
// ----------------------------------------------------
if (isset($_GET['acao_qtd']) && isset($_GET['id'])) {
    $idItem = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $tipoAcao = $_GET['acao_qtd'];

    if ($idItem && isset($_SESSION['carrinho'][$idItem])) {
        if ($tipoAcao === 'aumentar') {
            $_SESSION['carrinho'][$idItem]['quantidade'] += 1;
        } elseif ($tipoAcao === 'diminuir') {
            $_SESSION['carrinho'][$idItem]['quantidade'] -= 1;
            // Se a quantidade chegar a 0, remove do carrinho
            if ($_SESSION['carrinho'][$idItem]['quantidade'] <= 0) {
                unset($_SESSION['carrinho'][$idItem]);
            }
        }
    }
    header("Location: carrinho.php");
    exit;
}

// ----------------------------------------------------
// AÇÃO 3: Remover Item Específico
// ----------------------------------------------------
if (isset($_GET['remover'])) {
    $idRemover = filter_input(INPUT_GET, 'remover', FILTER_VALIDATE_INT);
    if ($idRemover && isset($_SESSION['carrinho'][$idRemover])) {
        unset($_SESSION['carrinho'][$idRemover]);
    }
    header("Location: carrinho.php");
    exit;
}

// ----------------------------------------------------
// AÇÃO 4: Esvaziar Todo o Carrinho
// ----------------------------------------------------
if (isset($_GET['limpar'])) {
    $_SESSION['carrinho'] = [];
    header("Location: carrinho.php");
    exit;
}

// ----------------------------------------------------
// Cálculo do Total Geral
// ----------------------------------------------------
$totalGeral = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $totalGeral += $item['preco'] * $item['quantidade'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLSHOP | Meu Carrinho</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="assets/css/style.css">
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
            <a href="produtos.php" class="btn btn-outline-light btn-sm me-2">
                <i class="bi bi-arrow-left"></i> Continuar Comprando
            </a>
            <a href="logout.php" class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </div>
    </div>
</nav>

<div class="container my-5 pt-5">
    <div class="text-center mb-4">
        <h1 class="fw-bold text-uppercase text-white"><i class="bi bi-cart3 me-2"></i>Seu Carrinho</h1>
        <p class="text-muted">Confira os itens selecionados antes de finalizar o pedido.</p>
    </div>

    <?php if (empty($_SESSION['carrinho'])): ?>
        <!-- Carrinho Vazio -->
        <div class="card card-register p-5 text-center mx-auto" style="max-width: 500px;">
            <i class="bi bi-cart-x text-muted display-1 mb-3"></i>
            <h4 class="text-white">O seu carrinho está vazio!</h4>
            <p class="text-muted">Navegue pelas ofertas e escolha os melhores produtos.</p>
            <a href="produtos.php" class="btn btn-clshop mt-3"><i class="bi bi-bag-check me-2"></i>Ver Produtos</a>
        </div>
    <?php else: ?>
        <!-- Tabela com Itens do Carrinho -->
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card card-register p-3">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Preço Un.</th>
                                    <th class="text-center">Quantidade</th>
                                    <th>Subtotal</th>
                                    <th class="text-center">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($_SESSION['carrinho'] as $id => $item): 
                                    $subtotal = $item['preco'] * $item['quantidade'];
                                ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?php echo htmlspecialchars($item['imagem']); ?>" alt="Foto" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;" class="me-3">
                                                <span class="fw-bold text-white"><?php echo htmlspecialchars($item['nome']); ?></span>
                                            </div>
                                        </td>
                                        <td>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="carrinho.php?acao_qtd=diminuir&id=<?php echo $id; ?>" class="btn btn-outline-secondary text-white">-</a>
                                                <span class="btn btn-secondary disabled text-white px-3 fw-bold"><?php echo $item['quantidade']; ?></span>
                                                <a href="carrinho.php?acao_qtd=aumentar&id=<?php echo $id; ?>" class="btn btn-outline-secondary text-white">+</a>
                                            </div>
                                        </td>
                                        <td class="text-success fw-bold">R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                                        <td class="text-center">
                                            <a href="carrinho.php?remover=<?php echo $id; ?>" class="btn btn-outline-danger btn-sm" title="Remover item">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-3 text-start">
                    <a href="carrinho.php?limpar=true" class="btn btn-outline-warning btn-sm">
                        <i class="bi bi-x-circle me-1"></i> Esvaziar Carrinho
                    </a>
                </div>
            </div>

            <!-- Resumo Financeiro do Pedido -->
            <div class="col-lg-4">
                <div class="card card-register p-4">
                    <h4 class="fw-bold text-white mb-3">Resumo do Pedido</h4>
                    <hr class="text-secondary">
                    <div class="d-flex justify-content-between text-white mb-2">
                        <span>Subtotal:</span>
                        <span>R$ <?php echo number_format($totalGeral, 2, ',', '.'); ?></span>
                    </div>
                    <div class="d-flex justify-content-between text-white mb-3">
                        <span>Frete:</span>
                        <span class="text-success fw-bold">GRÁTIS</span>
                    </div>
                    <hr class="text-secondary">
                    <div class="d-flex justify-content-between text-white fs-5 fw-bold mb-4">
                        <span>Total:</span>
                        <span class="text-success">R$ <?php echo number_format($totalGeral, 2, ',', '.'); ?></span>
                    </div>
                    <a href="finalizar.php" class="btn btn-clshop w-100 py-2 fs-6">
                        <i class="bi bi-credit-card me-2"></i> Finalizar Compra
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>