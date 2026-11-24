<?php
require_once __DIR__ . '/conexao.php';
redirecionarSeLogado();
$erro = '';
$email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $senha = (string) ($_POST['senha'] ?? '');
    $usuario = R::findOne('usuarios', ' email = ? ', [$email]);
    if (!$usuario || !password_verify($senha, (string) $usuario->senha)) {
        $erro = 'E-mail ou senha inválidos.';
    } else {
        iniciarSessao((int) $usuario->id);
        header('Location: index.php'); exit;
    }
}
cabecalho('Entrar');
?>
<section class="auth-layout"><div class="auth-card"><span class="auth-symbol">✦</span><span class="overline">SEU CAMINHO</span><h1>Entre na sua conta</h1><p>Continue acompanhando seus objetivos.</p><?php if ($erro): ?><div class="notice error"><?= e($erro) ?></div><?php endif; ?><form method="post"><div class="field"><label for="email">E-mail</label><input id="email" name="email" type="email" value="<?= e($email) ?>" required autofocus autocomplete="email"></div><div class="field"><label for="senha">Senha</label><input id="senha" name="senha" type="password" required autocomplete="current-password"></div><button class="save-button auth-submit" type="submit">Entrar</button></form><p class="auth-switch">Ainda não tem conta? <a href="cadastro.php">Criar conta</a></p></div></section>
<?php rodape(); ?>
