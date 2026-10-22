<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENCORE - Streaming de Filmes Musicais</title>
   <link rel="stylesheet" href="/STREAMING/css/style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="/streaming/img/ENCORE_fundo_transparente.png" width = "180px">
            
        </div>
        <nav>
            <a href="index.php">Início</a>
            <a href="catalogo.php">Explorar</a>
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <a href="favoritos.php">Minha lista</a>
            <?php endif; ?>
        </nav>
        <div class="nav-right">
            <form action="catalogo.php" method="GET" class="search-box">
                <input type="text" name="busca" placeholder="Pesquisar...">
            </form>
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <span style="font-size: 0.9rem; color: var(--creme);">Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></span>
                <a href="logout.php" class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.8rem;">Sair</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.8rem;">Entrar</a>
                <a href="cadastro.php" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8rem;">Cadastrar</a>
            <?php endif; ?>
        </div>
    </header>
    <div class="container">