<?php
require_once 'conexao.php';
include 'includes/header.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $pdo->prepare("SELECT f.*, c.nome as categoria_nome FROM filmes f JOIN categorias c ON f.categoria_id = c.id WHERE f.id = ?");
$stmt->execute([$id]);
$filme = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$filme) {
    echo "<h2 style='color: var(--rosa); text-align:center; margin: 50px 0;'>Filme não encontrado.</h2>";
    include 'includes/footer.php';
    exit;
}

// Ação de Favoritos via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $acao = $_POST['acao_favorito'] ?? '';
    
    if ($acao === 'adicionar') {
        $stmtFav = $pdo->prepare("INSERT IGNORE INTO favoritos (usuario_id, filme_id, criado_em) VALUES (?, ?, NOW())");
        $stmtFav->execute([$usuario_id, $id]);
    } elseif ($acao === 'remover') {
        $stmtFav = $pdo->prepare("DELETE FROM favoritos WHERE usuario_id = ? AND filme_id = ?");
        $stmtFav->execute([$usuario_id, $id]);
    }
    header("Location: detalhes.php?id=$id");
    exit;
}

// Buscar filmes recomendados da mesma categoria
$stmtRecomendados = $pdo->prepare("
    SELECT id, titulo, capa, ano
    FROM filmes
    WHERE categoria_id = ?
      AND id != ?
    ORDER BY RAND()
    LIMIT 6
");

$stmtRecomendados->execute([
    $filme['categoria_id'],
    $filme['id']
]);

$recomendados = $stmtRecomendados->fetchAll(PDO::FETCH_ASSOC);

$isFavorito = false;
if (isset($_SESSION['usuario_id'])) {
    $stmtCheckFav = $pdo->prepare("SELECT id FROM favoritos WHERE usuario_id = ? AND filme_id = ?");
    $stmtCheckFav->execute([$_SESSION['usuario_id'], $id]);
    $isFavorito = $stmtCheckFav->rowCount() > 0;
}
?>

<div class="card-detalhes" style="display: flex; gap: 30px; background: #161616; padding: 30px; border-radius: 12px; border: 1px solid #262626; flex-wrap: wrap;">
    <div style="flex: 0 0 250px;">
       <img src="<?php echo htmlspecialchars($filme['capa']); ?>" alt="<?php echo htmlspecialchars($filme['titulo']); ?>" style="width: 100%; border-radius: 8px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/250x350/1a1a1a/FFF3DF?text=ENCORE';">
    </div>
    <div style="flex: 1; min-width: 280px;">
        <span style="color: var(--rosa); font-size: 0.85rem; font-weight: bold; text-transform: uppercase;"><?php echo htmlspecialchars($filme['categoria_nome']); ?></span>
        <h1 style="font-size: 2.2rem; color: var(--creme); margin: 10px 0;"><?php echo htmlspecialchars($filme['titulo']); ?></h1>
        <div style="display: flex; gap: 15px; font-size: 0.9rem; color: var(--amarelo); margin-bottom: 15px;">
            <span>Ano: <?php echo $filme['ano']; ?></span>
            <span>Duração: <?php echo $filme['duracao']; ?> min</span>
            <span>Classificação: <?php echo htmlspecialchars($filme['classificacao']); ?></span>
        </div>
        <p style="color: #ccc; line-height: 1.6; margin-bottom: 25px;"><?php echo htmlspecialchars($filme['sinopse']); ?></p>
        
        <div>
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <form method="POST">
                    <?php if ($isFavorito): ?>
                        <input type="hidden" name="acao_favorito" value="remover">
                        <button type="submit" class="btn btn-secondary">&#10005; Remover dos Favoritos</button>
                    <?php else: ?>
                        <input type="hidden" name="acao_favorito" value="adicionar">
                        <button type="submit" class="btn btn-primary">&#9825; Adicionar aos Favoritos</button>
                    <?php endif; ?>
                </form>
            <?php else: ?>
                <a href="login.php" class="btn btn-primary">&#9825; Faça login para favoritar</a>
            <?php endif; ?>
            
   

<?php
$tituloFilme = mb_strtolower(trim($filme['titulo']));
?>

<?php if ($tituloFilme === 'grease'): ?>

    <div class="trailer-filme">
        <h2>Trailer</h2>

        <div class="video-container">
            <iframe
                src="https://www.youtube.com/embed/THd96gHV7Tg"
                title="Trailer de Grease"
                allowfullscreen>
            </iframe>
        </div>
    </div>

<?php elseif ($tituloFilme === 'aladdin' || $tituloFilme === 'aladin'): ?>

    <div class="trailer-filme">
        <h2>Trailer</h2>

        <div class="video-container">
            <iframe
                src="https://www.youtube.com/embed/eTjHiQKJUDY"
                title="Trailer de Aladdin"
                allowfullscreen>
            </iframe>
        </div>
    </div>

<?php endif; ?>


    
        </div>
    </div>

   
  

    
</div>

</div>



</div>










<section class="recomendacoes">
    <h2>Você também pode gostar</h2>
    

    <?php if (!empty($recomendados)): ?>
        <div class="grade-recomendacoes">
            <?php foreach ($recomendados as $recomendado): ?>
                <a
                    href="detalhes.php?id=<?php echo $recomendado['id']; ?>"
                    class="card-recomendado"
                >
                    <img
                        src="<?php echo htmlspecialchars($recomendado['capa']); ?>"
                        alt="<?php echo htmlspecialchars($recomendado['titulo']); ?>"
                        onerror="this.style.visibility='hidden';"
                    >

                    <div class="info-recomendado">
                        <h3>
                            <?php echo htmlspecialchars($recomendado['titulo']); ?>
                        </h3>

                        <span>
                            <?php echo htmlspecialchars((string) $recomendado['ano']); ?>
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="sem-recomendacoes">
            Em breve, teremos mais filmes para recomendar!
        </p>
    <?php endif; ?>
</section>



<?php include 'includes/footer.php'; ?>