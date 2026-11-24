<?php
require_once __DIR__ . '/conexao.php';
$usuario = exigirLogin();
$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$tarefa = R::findOne('tarefas', 'id = ? AND usuario_id = ?', [$id, $usuario['id']]);
if (!$tarefa || !$tarefa->id) { header('Location: index.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') { R::trash($tarefa); header('Location: index.php?excluida=1'); exit; }
cabecalho('Remover objetivo');
?>
<section class="confirm-layout">
    <a class="back-link" href="index.php">← Voltar para meu caminho</a>
    <div class="confirm-card"><span class="confirm-mark">×</span><span class="overline">REMOVER OBJETIVO</span><h1>Excluir este objetivo?</h1><p>O objetivo será retirado da sua lista e não contará mais no seu progresso.</p><div class="objective-preview"><strong><?= e($tarefa->titulo) ?></strong><span><?= e($tarefa->categoria) ?> · <?= $tarefa->prazo ? e(date('d/m/Y', strtotime((string) $tarefa->prazo))) : 'Sem prazo' ?></span></div><form method="post" class="confirm-actions"><input type="hidden" name="id" value="<?= (int) $tarefa->id ?>"><a class="cancel-button" href="index.php">Cancelar</a><button class="delete-button" type="submit">Excluir objetivo</button></form></div>
</section>
<?php rodape(); ?>
