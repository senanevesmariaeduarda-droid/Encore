<?php
require_once 'conexao.php';
$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (!empty($nome) && !empty($email) && !empty($senha)) {
        $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmtCheck->execute([$email]);
        if ($stmtCheck->rowCount() > 0) {
            $erro = "Este e-mail já está cadastrado.";
        } else {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $stmtInsert = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, criado_em) VALUES (?, ?, ?, NOW())");
            if ($stmtInsert->execute([$nome, $email, $senhaHash])) {
                $sucesso = "Conta criada com sucesso! <a href='login.php' style='color: var(--amarelo);'>Faça login</a>.";
            } else {
                $erro = "Erro ao cadastrar.";
            }
        }
    } else {
        $erro = "Preencha todos os campos.";
    }
}
include 'includes/header.php';
?>
<div style="max-width: 400px; margin: 40px auto; background: #161616; padding: 30px; border-radius: 10px; border: 1px solid #262626;">
    <h2 style="color: var(--creme); margin-bottom: 20px; text-align: center;">Criar Conta</h2>
    <?php if ($erro): ?><div style="background: rgba(232,139,170,0.2); color: var(--rosa); padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 0.9rem;"><?php echo $erro; ?></div><?php endif; ?>
    <?php if ($sucesso): ?><div style="background: rgba(242,182,50,0.2); color: var(--amarelo); padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 0.9rem;"><?php echo $sucesso; ?></div><?php endif; ?>
    <form action="cadastro.php" method="POST">
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-size: 0.9rem;">Nome</label>
            <input type="text" name="nome" required style="width: 100%; padding: 10px; background: #222; border: 1px solid #333; color: #fff; border-radius: 6px;">
        </div>
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-size: 0.9rem;">E-mail</label>
            <input type="email" name="email" required style="width: 100%; padding: 10px; background: #222; border: 1px solid #333; color: #fff; border-radius: 6px;">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-size: 0.9rem;">Senha</label>
            <input type="password" name="senha" required style="width: 100%; padding: 10px; background: #222; border: 1px solid #333; color: #fff; border-radius: 6px;">
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">Cadastrar</button>
    </form>
</div>
<?php include 'includes/footer.php'; ?>