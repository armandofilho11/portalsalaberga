# Sistema PDT

O sistema fica organizado por responsabilidade:

- `../app/main/config/`: conexão, segurança e migração do banco.
- `../app/main/config/database.sql`: arquivo único do esquema completo para instalações novas.
- `../app/main/config/migrations/`: alterações incrementais para bancos já existentes.
- `../app/main/controllers/`: endpoints e ações da aplicação.
- `../app/main/models/`: acesso e regras dos dados.
- `../app/main/views/`: telas de login e painéis por perfil.
- `../app/main/assets/images/`: imagens do sistema.
- `../app/main/assets/css/` e `../app/main/assets/js/`: estilos e scripts compartilhados.
- `tests/manual/`: verificações PHP e páginas de teste para desenvolvimento local. Os scripts PHP são bloqueados para acesso web.
- `tests/fixtures/`: referências antigas mantidas fora das rotas ativas.
- `docs/history/`: notas e guias de implementação anteriores.
- `docs/diagnostics/`: saídas de diagnóstico antigas.

O ponto de entrada do subsistema é `../default.php`, que encaminha para `app/main/index.php`.
Execute os testes PHP manuais a partir da raiz do subsistema:

```powershell
cd C:\xampp\htdocs\portalsalaberga\app\subsystems\system_pdt
php tests\manual\test_alunos.php
```

Os testes que criam dados devem ser executados somente em uma base de desenvolvimento.
As interfaces autenticadas usam os temas Claro e Escuro, com preferência isolada em `localStorage`.
