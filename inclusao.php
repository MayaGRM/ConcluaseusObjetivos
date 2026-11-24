<?php
require_once __DIR__ . '/conexao.php';
$usuario = exigirLogin();

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    if (mb_strlen($titulo) < 3) {
        $erro = 'Digite um objetivo com pelo menos 3 caracteres.';
    } else {
        $tarefa = R::dispense('tarefas');
        $tarefa->usuario_id = $usuario['id'];
        $tarefa->titulo = $titulo;
        $tarefa->descricao = trim($_POST['descricao'] ?? '') ?: null;
        $tarefa->categoria = trim($_POST['categoria'] ?? 'Pessoal') ?: 'Pessoal';
        $tarefa->prioridade = in_array($_POST['prioridade'] ?? 'Normal', ['Baixa', 'Normal', 'Alta'], true) ? $_POST['prioridade'] : 'Normal';
        $tarefa->prazo = $_POST['prazo'] ?? null ?: null;
        $tarefa->concluida = 0;
        R::store($tarefa);
        header('Location: index.php?sucesso=1');
        exit;
    }
}

cabecalho('Novo objetivo');
?>
<section class="form-layout">
    <a class="back-link" href="index.php">← Voltar para meu caminho</a>
    <div class="form-intro"><span class="overline">NOVO PASSO</span><h1>Qual é o seu próximo objetivo?</h1><p>Registre uma tarefa pequena e clara. O restante acontece no caminho.</p></div>
    <div class="form-card">
        <?php if ($erro): ?><div class="notice error"><?= e($erro) ?></div><?php endif; ?>
        <form method="post">
            <div class="field full"><label for="titulo">Nome do objetivo</label><input id="titulo" name="titulo" maxlength="180" required autofocus placeholder="Ex.: finalizar a documentação"></div>
            <div class="field full"><label for="descricao">Descrição <span>(opcional)</span></label><textarea id="descricao" name="descricao" placeholder="O que precisa ser feito?"></textarea></div>
            <div class="form-columns"><div class="field"><label for="prazo">Prazo <span>(opcional)</span></label><input id="prazo" type="date" name="prazo"></div><div class="field"><label for="categoria">Categoria</label><select id="categoria" name="categoria"><option>Pessoal</option><option>Estudos</option><option>Trabalho</option><option>Casa</option><option>Saúde</option></select></div><div class="field"><label for="prioridade">Prioridade</label><select id="prioridade" name="prioridade"><option>Normal</option><option>Alta</option><option>Baixa</option></select></div></div>
            <div class="form-actions"><a class="cancel-button" href="index.php">Cancelar</a><button class="save-button" type="submit">Salvar objetivo</button></div>
        </form>
    </div>
</section>
<?php rodape(); ?>
