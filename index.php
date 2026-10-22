<?php
require_once 'conexao.php';
include 'includes/header.php';

// Filme em destaque (último adicionado)
$stmtHero = $pdo->query("
    SELECT f.*, c.nome AS categoria_nome
    FROM filmes f
    JOIN categorias c ON f.categoria_id = c.id
    WHERE f.id = 15
    LIMIT 1
");
$filmeHero = $stmtHero->fetch(PDO::FETCH_ASSOC);

// Listar categorias
$stmtCats = $pdo->query("SELECT * FROM categorias");
$categorias = $stmtCats->fetchAll(PDO::FETCH_ASSOC);
?>


<?php if ($filmeHero): ?>
<section class="hero" style="
    background-image:
        linear-gradient(90deg, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.72) 45%, rgba(0,0,0,0.15) 100%),
        linear-gradient(0deg, rgba(0,0,0,0.65), transparent 50%),
        url('<?php echo htmlspecialchars(!empty($filmeHero['imagem_destaque']) ? $filmeHero['imagem_destaque'] : $filmeHero['capa'], ENT_QUOTES, 'UTF-8'); ?>');
    background-size: cover;
    background-position: center;
">

    <div class="hero-content">
        <div class="hero-tag">EM DESTAQUE</div>

        <?php if (!empty($filmeHero['logo_filme'])): ?>
            <img
                class="hero-logo"
                src="<?php echo htmlspecialchars($filmeHero['logo_filme'], ENT_QUOTES, 'UTF-8'); ?>"
                alt="<?php echo htmlspecialchars($filmeHero['titulo'], ENT_QUOTES, 'UTF-8'); ?>"
            >
        <?php else: ?>
            <h1><?php echo htmlspecialchars($filmeHero['titulo']); ?></h1>
        <?php endif; ?>

        <div class="hero-meta">
            <span><?php echo htmlspecialchars($filmeHero['ano']); ?></span>
            <span><?php echo htmlspecialchars($filmeHero['duracao']); ?> min</span>
            <span><?php echo htmlspecialchars($filmeHero['classificacao']); ?></span>
            <span><?php echo htmlspecialchars($filmeHero['categoria_nome']); ?></span>
        </div>

        <p class="hero-sinopse">
            <?php echo htmlspecialchars($filmeHero['sinopse']); ?>
        </p>

        <a href="detalhes.php?id=<?php echo (int)$filmeHero['id']; ?>"
           class="btn btn-primary">
            &#9654; Ver detalhes
        </a>
    </div>
</section>
<?php endif; ?>


<?php foreach ($categorias as $cat): 
    $stmtFilmes = $pdo->prepare("SELECT * FROM filmes WHERE categoria_id = ? LIMIT 10");
    $stmtFilmes->execute([$cat['id']]);
    $filmesCat = $stmtFilmes->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($filmesCat) > 0):
?>
    <div class="section-title"><?php echo htmlspecialchars($cat['nome']); ?></div>
    <div class="movie-grid">
        <?php foreach ($filmesCat as $filme): ?>
            <div class="movie-card">
                <a href="detalhes.php?id=<?php echo $filme['id']; ?>">
                   <img src="<?php echo htmlspecialchars($filme['capa']); ?>"" alt="<?php echo htmlspecialchars($filme['titulo']); ?>" onerror="this.src='https://via.placeholder.com/200x270/1a1a1a/FFF3DF?text=ENCORE';">
                </a>
                <div class="movie-info">
                    <div>
                        <h3><?php echo htmlspecialchars($filme['titulo']); ?></h3>
                        <p><?php echo $filme['ano']; ?> &bull; <?php echo $filme['duracao']; ?> min</p>
                    </div>
                    <a href="detalhes.php?id=<?php echo $filme['id']; ?>" class="btn btn-secondary" style="width:100%; justify-content:center; padding: 6px; font-size: 0.8rem;">Ver Mais</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php 
    endif;
endforeach; 

include 'includes/footer.php';
?>