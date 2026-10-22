<?php
require_once 'conexao.php';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        header("Location: index.php");
        exit;
    } else {
        $erro = "E-mail ou senha inválidos.";
    }
}
include 'includes/header.php';
?>
<div style="max-width: 400px; margin: 40px auto; background: #161616; padding: 30px; border-radius: 10px; border: 1px solid #262626;">
    <h2 style="color: var(--creme); margin-bottom: 20px; text-align: center;">Entrar no ENCORE</h2>
    <?php if ($erro): ?><div style="background: rgba(232,139,170,0.2); color: var(--rosa); padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 0.9rem;"><?php echo $erro; ?></div><?php endif; ?>
    <form action="login.php" method="POST">
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-size: 0.9rem;">E-mail</label>
            <input type="email" name="email" required style="width: 100%; padding: 10px; background: #222; border: 1px solid #333; color: #fff; border-radius: 6px;">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-size: 0.9rem;">Senha</label>
            <input type="password" name="senha" required style="width: 100%; padding: 10px; background: #222; border: 1px solid #333; color: #fff; border-radius: 6px;">
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">Entrar</button>
    </form>
</div>
<?php include 'includes/footer.php'; ?>