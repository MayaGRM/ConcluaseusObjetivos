# ✦ Conclua seus objetivos

Aplicação web simples para organizar tarefas pessoais e acompanhar o progresso. Cada usuário cria sua conta, cadastra objetivos com categoria, prioridade e prazo, e vê quantos já foram concluídos.

## Funcionalidades

- Cadastro, login e logout de usuários (senhas protegidas com `password_hash()`)
- Objetivos privados: cada conta só enxerga e altera os seus
- Criação de objetivos com título, descrição, categoria, prioridade e prazo
- Marcar como concluído ou voltar para pendente
- Exclusão com tela de confirmação
- Filtros: todas, pendentes e concluídas
- Painel com total de objetivos, em andamento, concluídos e percentual de progresso

## Tecnologias

- PHP 7.3 ou superior
- MySQL / MariaDB
- [RedBeanPHP](https://redbeanphp.com/) 5.7 (ORM, já incluído em `class/rb.php`)
- HTML e CSS puro, sem dependências externas

## Estrutura do projeto

```
├── assets/css/
│   ├── style.css       # estilos gerais
│   └── auth.css        # estilos de login e cadastro
├── class/rb.php        # RedBeanPHP
├── auth.php            # sessão e controle de acesso
├── conexao.php         # conexão com o banco, criação das tabelas e layout
├── index.php           # painel "Meu caminho"
├── inclusao.php        # novo objetivo
├── exclusao.php        # remoção de objetivo
├── login.php           # entrar
├── cadastro.php        # criar conta
└── logout.php          # sair
```

## Configuração do banco de dados

As credenciais são definidas no início do `conexao.php`. Cada valor pode ser substituído por uma variável de ambiente:

| Variável  | Descrição            | Padrão local      |
| --------- | -------------------- | ----------------- |
| `DB_HOST` | Servidor MySQL       | `127.0.0.1`       |
| `DB_PORT` | Porta                | `3306`            |
| `DB_NAME` | Nome do banco        | `gestao_tarefas`  |
| `DB_USER` | Usuário              | `root`            |
| `DB_PASS` | Senha                | vazia             |

As tabelas `usuarios` e `tarefas` são criadas automaticamente no primeiro acesso. Não é preciso importar nenhum arquivo SQL.

> ⚠️ Não publique o `conexao.php` com senha real em repositórios públicos.

## Rodando localmente

1. Instale PHP e MySQL (ou use XAMPP / Laragon).
2. Crie um banco chamado `gestao_tarefas`.
3. Ajuste as credenciais em `conexao.php` ou defina as variáveis de ambiente.
4. Na pasta do projeto, execute:

   ```bash
   php -S localhost:8000
   ```

5. Abra `http://localhost:8000` e crie sua conta.

## Publicando no InfinityFree

1. No painel, crie o banco em **MySQL Databases** e anote host, usuário, senha e nome do banco.
2. Preencha esses dados no `conexao.php`.
3. Conecte por FTP (por exemplo, com o FileZilla) usando os dados de **FTP Details**.
4. Envie **o conteúdo** do projeto para dentro da pasta `htdocs`, sem criar uma subpasta.
5. Apague o `index2.html` padrão, se existir.
6. Acesse o endereço do site e crie a primeira conta.

Dicas:

- Prefira FTP em vez do gerenciador de arquivos online, que costuma falhar ao extrair `.zip`.
- Se aparecer tela branca, selecione o PHP 7.3 ou superior em **Select PHP Version** no painel.
- O banco do InfinityFree só aceita conexões vindas da própria hospedagem, então não dá para acessá-lo do computador local.

## Segurança

- Senhas armazenadas apenas como hash
- Consultas com parâmetros (proteção contra SQL Injection)
- Saída escapada com `htmlspecialchars` (proteção contra XSS)
- Cookies de sessão com `HttpOnly` e `Secure` quando o site usa HTTPS
- ID da sessão regenerado a cada login

## Observação sobre contas

A primeira conta criada recebe automaticamente qualquer objetivo antigo que não tenha dono, para facilitar a migração de dados de versões sem login.
