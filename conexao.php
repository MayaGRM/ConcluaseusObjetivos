<?php
declare(strict_types=1);

require_once __DIR__ . '/class/rb.php';
require_once __DIR__ . '/auth.php';

// Credenciais do MySQL no InfinityFree (variáveis de ambiente, se existirem, têm prioridade)
$host = getenv('DB_HOST') ?: 'sql202.infinityfree.com';
$port = getenv('DB_PORT') ?: '3306';
$name = getenv('DB_NAME') ?: 'if0_43526792_conclua';
$user = getenv('DB_USER') ?: 'if0_43526792';
$pass = getenv('DB_PASS') ?: 'QGpVsDgYtfebbVA';

R::setup("mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4", $user, $pass);
R::exec('CREATE TABLE IF NOT EXISTS usuarios (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(100) NOT NULL, email VARCHAR(190) NOT NULL UNIQUE, senha VARCHAR(255) NOT NULL, criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB');
R::exec('CREATE TABLE IF NOT EXISTS tarefas (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, usuario_id INT UNSIGNED NULL, titulo VARCHAR(180) NOT NULL, descricao TEXT NULL, categoria VARCHAR(80) NOT NULL DEFAULT "Pessoal", prioridade ENUM("Baixa", "Normal", "Alta") NOT NULL DEFAULT "Normal", prazo DATE NULL, concluida TINYINT(1) NOT NULL DEFAULT 0, criada_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP, atualizada_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, INDEX idx_tarefas_usuario (usuario_id), INDEX idx_tarefas_status (concluida), INDEX idx_tarefas_prazo (prazo)) ENGINE=InnoDB');
try { R::exec('ALTER TABLE tarefas ADD COLUMN usuario_id INT UNSIGNED NULL AFTER id'); } catch (Throwable $e) { /* já existe */ }

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function cabecalho(string $titulo): void
{
    $usuario = usuarioAtual();
    ?>
    <!doctype html>
    <html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= e($titulo) ?> · Conclua seus objetivos</title>
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/css/auth.css">
    </head>
    <body>
        <header class="site-header">
            <a class="logo" href="index.php">
                <span class="logo-mark">✦</span>
                <span>Conclua seus objetivos</span>
            </a>
            <nav class="top-nav">
                <a href="index.php">Meu caminho</a>
                <a class="nav-cta" href="inclusao.php">+ Novo objetivo</a>
                <?php if ($usuario): ?><span class="account-name"><?= e($usuario['nome']) ?></span><a class="logout-link" href="logout.php">Sair</a><?php endif; ?>
            </nav>
        </header>
        <main class="shell">
    <?php
}

function rodape(): void
{
    ?>
        </main>
        <footer class="site-footer">Conclua seus objetivos <span>·</span> organização simples para o dia a dia</footer>
    </body>
    </html>
    <?php
}
