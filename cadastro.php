<?php
require_once __DIR__ . '/conexao.php';
redirecionarSeLogado();
$erro = ''; $nome = ''; $email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? ''); $email = strtolower(trim($_POST['email'] ?? ''));
    $senha = (string) ($_POST['senha'] ?? ''); $confirmacao = (string) ($_POST['confirmacao'] ?? '');
    if (mb_strlen($nome) < 2) $erro = 'Digite seu nome.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erro = 'Digite um e-mail válido.';
    elseif (mb_strlen($senha) < 8) $erro = 'A senha deve ter pelo menos 8 caracteres.';
    elseif ($senha !== $confirmacao) $erro = 'As senhas não coincidem.';
    elseif (R::count('usuarios', 'email = ?', [$email]) > 0) $erro = 'Já existe uma conta com este e-mail.';
    else {
        $primeiraConta = R::count('usuarios') === 0;
        $usuario = R::dispense('usuarios'); $usuario->nome = $nome; $usuario->email = $email; $usuario->senha = password_hash($senha, PASSWORD_DEFAULT);
        $id = R::store($usuario);
        if ($primeiraConta) R::exec('UPDATE tarefas SET usuario_id = ? WHERE usuario_id IS NULL', [$id]);
        iniciarSessao((int) $id); header('Location: index.php'); exit;
    }
}
cabecalho('Criar conta');
?>
<section class="auth-layout"><div class="auth-card"><span class="auth-symbol">✦</span><span class="overline">COMECE AGORA</span><h1>Crie sua conta</h1><p>Seus objetivos ficam privados e separados.</p><?php if ($erro): ?><div class="notice error"><?= e($erro) ?></div><?php endif; ?><form method="post"><div class="field"><label for="nome">Nome</label><input id="nome" name="nome" value="<?= e($nome) ?>" required maxlength="100" autocomplete="name"></div><div class="field"><label for="email">E-mail</label><input id="email" name="email" type="email" value="<?= e($email) ?>" required autocomplete="email"></div><div class="field"><label for="senha">Senha <span>(mínimo de 8 caracteres)</span></label><input id="senha" name="senha" type="password" minlength="8" required autocomplete="new-password"></div><div class="field"><label for="confirmacao">Confirmar senha</label><input id="confirmacao" name="confirmacao" type="password" minlength="8" required autocomplete="new-password"></div><button class="save-button auth-submit" type="submit">Criar conta</button></form><p class="auth-switch">Já tem conta? <a href="login.php">Entrar</a></p></div></section>
<?php rodape(); ?>
