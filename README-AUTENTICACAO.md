# Autenticação

O projeto possui criação de conta, login e logout. As senhas usam `password_hash()` e cada objetivo é vinculado ao `usuario_id` da sessão atual. Listagem, conclusão e remoção só operam sobre objetivos da conta autenticada.

A tabela `usuarios` e a coluna `tarefas.usuario_id` são criadas/migradas automaticamente na inicialização. A primeira conta recebe objetivos legados sem proprietário.
