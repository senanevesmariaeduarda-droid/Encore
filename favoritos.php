<?php
require_once 'conexao.php';
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
include 'includes/header.php';

$usuario_id = $_SESSION['usuario_id'];
$stmt = $pdo->prepare("SELECT f.*, c.nome as categoria_nome FROM favoritos fav JOIN filmes f ON fav.filme_id = f.id JOIN categorias c ON f.categoria_id = c.id WHERE fav.usuario_id = ? ORDER BY fav.criado_em DESC");
$stmt->execute([$usuario_id]);
$favoritos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="section-title">Minha Lista de Favoritos</h2>

<?php if (count($favoritos) > 0): ?>
    <div class="movie-grid">
        <?php foreach ($favoritos as $filme): ?>
            <div class="movie-card">
                <a href="detalhes.php?id=<?php echo $filme['id']; ?>">
                    <img src="<?php echo htmlspecialchars($filme['capa']); ?>" alt="<?php echo htmlspecialchars($filme['titulo']); ?>" onerror="this.src='https://via.placeholder.com/200x270/1a1a1a/FFF3DF?text=ENCORE';">
                </a>
                <div class="movie-info">
                    <div>
                        <h3><?php echo htmlspecialchars($filme['titulo']); ?></h3>
                        <p><?php echo $filme['ano']; ?> &bull; <?php echo htmlspecialchars($filme['categoria_nome']); ?></p>
                    </div>
                    <a href="detalhes.php?id=<?php echo $filme['id']; ?>" class="btn btn-secondary" style="width:100%; justify-content:center; padding: 6px; font-size: 0.8rem;">Ver Mais</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p style="text-align: center; color: #888; padding: 40px;">Você ainda não adicionou nenhum filme aos favoritos.</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>