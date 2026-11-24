<?php
require_once __DIR__ . '/conexao.php';
$usuario = exigirLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'alternar') {
    $tarefa = R::findOne('tarefas', 'id = ? AND usuario_id = ?', [(int) ($_POST['id'] ?? 0), $usuario['id']]);
    if ($tarefa && $tarefa->id) {
        $tarefa->concluida = $tarefa->concluida ? 0 : 1;
        R::store($tarefa);
    }
    header('Location: index.php');
    exit;
}

$filtro = $_GET['filtro'] ?? 'todas';
$ordem = 'ORDER BY concluida ASC, prazo IS NULL ASC, prazo ASC, criada_em DESC';

if ($filtro === 'pendentes') {
    $tarefas = R::findAll('tarefas', 'WHERE usuario_id = ' . (int) $usuario['id'] . ' AND concluida = 0 ' . $ordem);
} elseif ($filtro === 'concluidas') {
    $tarefas = R::findAll('tarefas', 'WHERE usuario_id = ' . (int) $usuario['id'] . ' AND concluida = 1 ' . $ordem);
} else {
    $tarefas = R::findAll('tarefas', 'WHERE usuario_id = ' . (int) $usuario['id'] . ' ' . $ordem);
}

$total = R::count('tarefas', 'usuario_id = ?', [$usuario['id']]);
$concluidas = R::count('tarefas', 'usuario_id = ? AND concluida = 1', [$usuario['id']]);
$pendentes = $total - $concluidas;
$percentual = $total ? (int) round(($concluidas / $total) * 100) : 0;

cabecalho('Meu caminho');
?>
<section class="hero">
    <div>
        <span class="overline">PAINEL DE OBJETIVOS</span>
        <h1>Conclua seus objetivos.</h1>
        <p>Organize suas tarefas e acompanhe o que já foi concluído.</p>
    </div>
</section>

<?php if (isset($_GET['sucesso'])): ?>
    <div class="notice">Objetivo adicionado ao seu caminho.</div>
<?php endif; ?>
<?php if (isset($_GET['excluida'])): ?>
    <div class="notice">Objetivo removido.</div>
<?php endif; ?>
<?php if (isset($_GET['editada'])): ?>
    <div class="notice">Objetivo atualizado com sucesso.</div>
<?php endif; ?>

<section class="stats-grid">
    <article class="stat-card coral"><span class="stat-icon">○</span><small>OBJETIVOS TOTAIS</small><strong><?= $total ?></strong></article>
    <article class="stat-card blue"><span class="stat-icon">◷</span><small>EM ANDAMENTO</small><strong><?= $pendentes ?></strong></article>
    <article class="stat-card green"><span class="stat-icon">✦</span><small>CONCLUÍDOS</small><strong><?= $concluidas ?></strong></article>
    <article class="stat-card lilac"><span class="stat-icon">↗</span><small>PROGRESSO GERAL</small><strong><?= $percentual ?>%</strong></article>
</section>

<section class="content-grid">
    <div class="list-card">
        <div class="list-heading">
            <div><span class="overline">SEU PLANEJAMENTO</span><h2><?= $filtro === 'todas' ? 'Todos os objetivos' : ucfirst($filtro) ?></h2></div>
            <a class="new-button" href="inclusao.php">+ Novo objetivo</a>
        </div>
        <div class="filters">
            <a class="<?= $filtro === 'todas' ? 'active' : '' ?>" href="?filtro=todas">Todos <b><?= $total ?></b></a>
            <a class="<?= $filtro === 'pendentes' ? 'active' : '' ?>" href="?filtro=pendentes">Em andamento <b><?= $pendentes ?></b></a>
            <a class="<?= $filtro === 'concluidas' ? 'active' : '' ?>" href="?filtro=concluidas">Concluídos <b><?= $concluidas ?></b></a>
        </div>
        <?php if (!$tarefas): ?>
            <div class="empty-state"><span class="empty-star">✦</span><strong>Nenhum objetivo por aqui.</strong><p>Adicione seu próximo passo para começar.</p><a class="new-button" href="inclusao.php">Criar objetivo</a></div>
        <?php else: ?>
            <div class="goal-list">
                <?php foreach ($tarefas as $tarefa): ?>
                    <article class="goal-row <?= $tarefa->concluida ? 'is-done' : '' ?>">
                        <form method="post" class="goal-check-form">
                            <input type="hidden" name="acao" value="alternar">
                            <input type="hidden" name="id" value="<?= (int) $tarefa->id ?>">
                            <button class="goal-check" type="submit" title="Alternar conclusão"><?= $tarefa->concluida ? '✓' : '' ?></button>
                        </form>
                        <div class="goal-copy"><strong><?= e($tarefa->titulo) ?></strong><?php if ($tarefa->descricao): ?><span><?= e(mb_strimwidth($tarefa->descricao, 0, 92, '…')) ?></span><?php endif; ?></div>
                        <div class="goal-meta"><span class="category"><?= e($tarefa->categoria) ?></span><span><?= $tarefa->prazo ? e(date('d/m/Y', strtotime((string) $tarefa->prazo))) : 'Sem prazo' ?></span></div>
                        <div class="goal-actions">
                            <a class="edit-action" href="editar.php?id=<?= (int) $tarefa->id ?>" title="Editar objetivo" aria-label="Editar objetivo">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4Z"/></svg>
                                <span>Editar</span>
                            </a>
                            <a class="remove" href="exclusao.php?id=<?= (int) $tarefa->id ?>" title="Excluir objetivo" aria-label="Excluir objetivo">×</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <aside class="side-card">
        <span class="overline">COMO ESTÁ INDO</span>
        <h2><?= $percentual >= 70 ? 'Ritmo excelente.' : 'Continue avançando.' ?></h2>
        <p>Você concluiu <?= $concluidas ?> de <?= $total ?> objetivo(s).</p>
        <div class="big-progress"><span style="width: <?= $percentual ?>%"></span></div>
        <div class="progress-label"><span>Progresso</span><strong><?= $percentual ?>%</strong></div>
    </aside>
</section>
<?php rodape(); ?>
