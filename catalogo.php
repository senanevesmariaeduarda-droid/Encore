<?php
require_once 'conexao.php';
include 'includes/header.php';

$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$categoria_id = isset($_GET['categoria']) ? intval($_GET['categoria']) : 0;
$ordenacao = isset($_GET['ordenacao']) ? $_GET['ordenacao'] : 'titulo';

$sql = "SELECT f.*, c.nome as categoria_nome FROM filmes f JOIN categorias c ON f.categoria_id = c.id WHERE 1=1";
$params = [];

if ($busca !== '') {
    $sql .= " AND (f.titulo LIKE ? OR f.sinopse LIKE ?)";
    $params[] = "%$busca%";
    $params[] = "%$busca%";
}

if ($categoria_id > 0) {
    $sql .= " AND f.categoria_id = ?";
    $params[] = $categoria_id;
}

if ($ordenacao === 'recente') {
    $sql .= " ORDER BY f.ano DESC";
} elseif ($ordenacao === 'antigo') {
    $sql .= " ORDER BY f.ano ASC";
} else {
    $sql .= " ORDER BY f.titulo ASC";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$filmes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmtCats = $pdo->query("SELECT * FROM categorias");
$categorias = $stmtCats->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="section-title">Explorar Catálogo</h2>

<form method="GET" action="catalogo.php" style="background: #161616; padding: 20px; border-radius: 10px; margin-bottom: 30px; display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end; border: 1px solid #262626;">
    <div style="flex: 1; min-width: 200px;">
        <label style="display: block; font-size: 0.85rem; margin-bottom: 5px;">Pesquisar</label>
        <input type="text" name="busca" value="<?php echo htmlspecialchars($busca); ?>" placeholder="Título ou sinopse..." style="width: 100%; padding: 8px; background: #222; border: 1px solid #333; color: #fff; border-radius: 6px;">
    </div>
    <div>
        <label style="display: block; font-size: 0.85rem; margin-bottom: 5px;">Categoria</label>
        <select name="categoria" style="padding: 8px; background: #222; border: 1px solid #333; color: #fff; border-radius: 6px;">
            <option value="0">Todas as categorias</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?php echo $cat['id']; ?>" <?php echo $categoria_id === $cat['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['nome']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label style="display: block; font-size: 0.85rem; margin-bottom: 5px;">Ordenação</label>
        <select name="ordenacao" style="padding: 8px; background: #222; border: 1px solid #333; color: #fff; border-radius: 6px;">
            <option value="titulo" <?php echo $ordenacao === 'titulo' ? 'selected' : ''; ?>>Alfabética</option>
            <option value="recente" <?php echo $ordenacao === 'recente' ? 'selected' : ''; ?>>Lançamento mais recente</option>
            <option value="antigo" <?php echo $ordenacao === 'antigo' ? 'selected' : ''; ?>>Lançamento mais antigo</option>
        </select>
    </div>
    <div>
        <button type="submit" class="btn btn-primary" style="padding: 9px 20px;">Filtrar</button>
        <a href="catalogo.php" class="btn btn-secondary" style="padding: 9px 15px;">Limpar</a>
    </div>
</form>

<p style="margin-bottom: 20px; font-size: 0.9rem; color: #aaa;"><?php echo count($filmes); ?> filme(s) encontrado(s).</p>

<?php if (count($filmes) > 0): ?>
    <div class="movie-grid">
        <?php foreach ($filmes as $filme): ?>
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
    <p style="text-align: center; color: #888; padding: 40px;">Nenhum filme encontrado.</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>