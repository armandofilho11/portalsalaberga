# Expansão do Sistema PDT

## Auditoria inicial

O subsistema está em `app/main/` e usa PHP, PDO e MySQL, com modelos, controladores, views por perfil e uma migração incremental. O banco existente já cobre usuários, cursos, turmas, alunos, notas, frequência, ocorrências e notificações. A tela de calendário consolida ocorrências e frequências, mas não existe cadastro de eventos. Não foram encontrados módulos ou tabelas de acompanhamento individual ou conselho de classe. O documento funcional PDF/DOCX citado na solicitação não está no repositório.

Achados que orientam as próximas etapas:

- `classificarDesempenho()` considerava notas de 5,9 a 6,0 como Crítico, enquanto `calcularDesempenhoMedioProfessor()` aplicava outro intervalo.
- Turmas sem notas eram classificadas como nota zero ou Adequado, dependendo do cálculo.
- `podeVisualizarTurma()` permitia a qualquer usuário autenticado não gestor visualizar qualquer turma; agora o acesso a dados de alunos/turma exige vínculo do professor responsável (PDT). Todos os professores podem consultar os cards informativos de todas as turmas, sem abrir seus registros pedagógicos.
- O lançamento de notas recebia turma e aluno separadamente sem conferir a relação; agora a validação é feita no modelo para operações individuais e em lote.
- A chave única de `media_final` é aluno + bimestre + disciplina. Ela não inclui turma nem ano letivo, portanto uma migração de histórico requer examinar registros e resolver a regra de retenção antes de alterar a chave.
- A tela de calendário é somente leitura e deriva seus eventos de ocorrências e frequência; não há infraestrutura de reuniões, ações/encaminhamentos ou histórico de acompanhamento.

## Implementado nesta etapa

- Criada uma classificação central reutilizável, com os critérios institucionais do sistema: Adequado `>= 7,0`; Intermediário `>= 5,9 e < 7,0`; Crítico `> 4,0 e < 5,9`; Muito Crítico `<= 4,0`. Esses limites são regra fixa do sistema, não uma configuração por usuário.
- Notas nulas ou ausentes permanecem sem classificação. Valores não numéricos ou fora do intervalo de 0 a 10 são tratados como inválidos.
- A média da turma considera somente notas válidas e informa a situação e a contagem de notas válidas/inválidas.
- O salvamento de notas valida intervalo, bimestre, turma/aluno e evita sobrescrever uma nota já associada a turma diferente ou a um histórico sem turma.
- O endpoint de desempenho do professor passa a usar o ID da sessão para professores, em vez de confiar no ID enviado pelo navegador. Gestores podem consultar um ID explícito.
- Adicionado teste independente para os limites 3,9; 4,0; 4,1; 5,8; 5,9; 6,0; 6,9; 7,0 e 7,1, notas ausentes e notas inválidas.
- Criado perfil pedagógico de leitura com identificação escolar mínima, notas por disciplina/bimestre, frequência registrada e ocorrências não excluídas. Exibe estados vazios sem inventar dados.
- O perfil é servido por endpoint que valida o vínculo do professor com a turma no servidor; a tela do professor disponibiliza a consulta pela lista de alunos.
- Conforme a regra institucional esclarecida, o vínculo já existente entre turma e professor é o vínculo PDT; não foi criada uma segunda atribuição de professor.
- A seção de turmas do professor lista todas as turmas cadastradas, mas só oferece acesso aos registros da turma vinculada ao professor como PDT. A autorização também é verificada nos endpoints, não apenas na interface.
- Criado acompanhamento pedagógico individual com registro, motivo, encaminhamento, data prevista de retorno e fluxo de status.
- Criado registro de conselho por turma/bimestre, com pauta, ata, deliberações individuais e finalização que bloqueia novas edições.
- Reativado o calendário para PDTs e adicionadas as datas dos conselhos. As consultas seguem o filtro de turma do usuário e os textos vindos do banco são inseridos como texto, sem interpretação HTML.
- Reativada a área de relatórios apenas para PDTs, com contagens de acompanhamentos/conselhos, indicadores por turma e exportação CSV/XLS. Os cálculos por turma foram separados por consulta para não multiplicar frequência e ocorrências ao combinar várias relações.
- A área do gestor agora tem navegação funcional para início, turmas, ocorrências escolares e indicadores gerais; a consulta de ocorrências usa o filtro de período e o painel carrega os indicadores consolidados da escola.
- Notificações de novos acompanhamentos, conselhos e deliberações individuais são direcionadas aos usuários gestores.
- As novas operações verificam vínculo com turma e pertencimento do aluno no servidor. Os registros ficam visíveis somente a usuários autorizados para aquela turma.
- As tabelas incrementais estão em `app/main/config/migrations/002_acompanhamento_conselho.sql` e também no esquema para instalações novas (`database.sql`). A migração ainda precisa ser aplicada no banco local antes de usar as telas.
- O esquema de notificações agora inclui os campos de entidade já consumidos por `NotificacaoModel`; bancos existentes precisam aplicar `003_notificacoes_entidade.sql`.

## Decisões e pendências antes de produção

- O perfil de coordenador ainda não tem vínculo institucional definido com turma, curso ou área. A regra implementada concede acesso detalhado ao PDT responsável e mantém os acessos administrativos já existentes para gestor; coordenador não ganha acesso a dados de alunos apenas pelo perfil.
- Definir disciplina, período letivo e histórico de transferência/avanço antes de alterar a unicidade de notas ou criar novas relações históricas.
- O calendário lista ocorrências, frequência e conselhos; ainda falta decidir quais tipos de eventos escolares devem ser cadastráveis.
- Definir quem deve receber notificações dos novos registros e quais eventos escolares podem ser cadastrados.
- Não foi executada migração de banco nem integração com MySQL nesta etapa.
