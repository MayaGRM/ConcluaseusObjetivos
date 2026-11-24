<?php
require_once __DIR__ . '/conexao.php';
$usuario = exigirLogin();

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$tarefa = R::findOne('tarefas', 'id = ? AND usuario_id = ?', [$id, $usuario['id']]);

if (!$tarefa || !$tarefa->id) {
    header('Location: index.php');
    exit;
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $categoria = trim($_POST['categoria'] ?? 'Pessoal');
    $prioridade = $_POST['prioridade'] ?? 'Normal';
    $prazo = trim($_POST['prazo'] ?? '');

    if (mb_strlen($titulo) < 3) {
        $erro = 'Digite um objetivo com pelo menos 3 caracteres.';
    } elseif ($prazo !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $prazo)) {
        $erro = 'Informe uma data válida.';
    } elseif (!in_array($prioridade, ['Baixa', 'Normal', 'Alta'], true)) {
        $erro = 'Selecione uma prioridade válida.';
    } else {
        $tarefa->titulo = $titulo;
        $tarefa->descricao = $descricao ?: null;
        $tarefa->categoria = $categoria ?: 'Pessoal';
        $tarefa->prioridade = $prioridade;
        $tarefa->prazo = $prazo ?: null;
        // Não altera o campo concluida: o status atual é preservado.
        R::store($tarefa);
        header('Location: index.php?editada=1');
        exit;
    }
}

cabecalho('Editar objetivo');
?>
<section class="form-layout">
    <a class="back-link" href="index.php">← Voltar para meu caminho</a>
    <div class="form-intro">
        <span class="overline">AJUSTE SEU PLANEJAMENTO</span>
        <h1>Editar objetivo</h1>
        <p>Atualize os detalhes deste objetivo e mantenha seu caminho em dia.</p>
    </div>
    <div class="form-card">
        <?php if ($erro): ?><div class="notice error"><?= e($erro) ?></div><?php endif; ?>
        <form method="post" action="editar.php">
            <input type="hidden" name="id" value="<?= (int) $tarefa->id ?>">
            <div class="field full">
                <label for="titulo">Nome do objetivo</label>
                <input id="titulo" name="titulo" maxlength="180" minlength="3" required value="<?= e((string) $tarefa->titulo) ?>">
            </div>
            <div class="field full">
                <label for="descricao">Descrição <span>(opcional)</span></label>
                <textarea id="descricao" name="descricao"><?= e((string) $tarefa->descricao) ?></textarea>
            </div>
            <div class="form-columns">
                <div class="field">
                    <label for="prazo">Prazo <span>(opcional)</span></label>
                    <input id="prazo" type="date" name="prazo" value="<?= e($tarefa->prazo ? (string) $tarefa->prazo : '') ?>">
                </div>
                <div class="field">
                    <label for="categoria">Categoria</label>
                    <select id="categoria" name="categoria">
                        <?php foreach (['Pessoal', 'Estudos', 'Trabalho', 'Casa', 'Saúde'] as $opcao): ?>
                            <option value="<?= e($opcao) ?>" <?= $tarefa->categoria === $opcao ? 'selected' : '' ?>><?= e($opcao) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label for="prioridade">Prioridade</label>
                    <select id="prioridade" name="prioridade">
                        <?php foreach (['Normal', 'Alta', 'Baixa'] as $opcao): ?>
                            <option value="<?= e($opcao) ?>" <?= $tarefa->prioridade === $opcao ? 'selected' : '' ?>><?= e($opcao) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <a class="cancel-button" href="index.php">Cancelar</a>
                <button class="save-button" type="submit">✓ Salvar alterações</button>
            </div>
        </form>
    </div>
</section>
<?php rodape(); ?>
