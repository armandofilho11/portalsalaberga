# Implementação de Ocorrências - Guia de Setup

## ✅ O que foi implementado:

### 1. **Interface do Usuário**
- ✅ Caixa de pesquisa de alunos no modal (já existia)
- ✅ Botão "Aplicar Ocorrência" vermelho com ícone de alerta (já existia)
- ✅ Modal completo com:
  - Seleção de turma
  - Seleção de aluno (carregado dinamicamente)
  - Select com 30+ tipos de ocorrência
  - Campo de descrição (opcional)

### 2. **Backend**
- ✅ Controller: `ocorrencia_controller.php`
- ✅ Modelo: `ocorrencia_model.php`
- ✅ Esquema SQL com tabelas criadas em `database.sql`

---

## 🔧 Próximas Etapas:

### 1. **Configurar o banco**
Execute no phpMyAdmin ou MySQL Client:

```text
Para uma instalação nova, importe app/main/config/database.sql.
Para uma instalação existente, aplique somente as migrações pendentes em
app/main/config/migrations/.
```

### 2. **Testar a Funcionalidade**
1. Acesse a página de professor (`professor.php`)
2. Clique em **"Minhas turmas"** no menu lateral
3. Clique em **"Consultar"** em uma turma
4. Veja a lista de alunos com a **caixa de pesquisa**
5. Clique no botão vermelho **"Aplicar ocorrência"**
6. Preencha o modal e clique em **"Salvar ocorrência"**

---

## 📋 Tipos de Ocorrência Disponíveis:
- Chegou atrasado
- Falta sem justificativa
- Falta justificada
- Saiu mais cedo
- Não trouxe material
- Conversa excessiva em sala
- Desrespeito ao professor
- Desrespeito aos colegas
- Uso indevido de celular
- Não realizou atividade
- Dormiu em sala
- Briga entre alunos
- Bullying
- Danificação de patrimônio escolar
- Uniforme incompleto
- Excelente comportamento
- Participação positiva
- Destaque acadêmico
- Advertência verbal
- Advertência escrita
- Suspensão
- Encaminhamento à coordenação
- Responsável convocado
- Uso de linguagem inadequada
- Cola em avaliação
- Entrada sem autorização
- Saída sem autorização
- Atraso na entrega de trabalhos
- Comportamento inadequado no intervalo

---

## 🗄️ Estrutura do Banco de Dados:

### Tabela: `ocorrencias`
```sql
CREATE TABLE ocorrencias (
    id_ocorrencia INT PRIMARY KEY AUTO_INCREMENT,
    id_aluno INT NOT NULL,
    id_turma INT NOT NULL,
    id_professor INT NOT NULL,
    tipo_ocorrencia VARCHAR(100) NOT NULL,
    descricao TEXT,
    data_ocorrencia TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_aluno) REFERENCES alunos(id_aluno),
    FOREIGN KEY (id_turma) REFERENCES turmas(id_turma),
    FOREIGN KEY (id_professor) REFERENCES usuarios(id)
);
```

---

## 🚀 Funcionalidades Adicionais (já incluídas):

1. **Pesquisa de Alunos**: Digite no campo de busca para filtrar alunos por nome, matrícula ou email
2. **Carregamento Dinâmico**: Ao selecionar uma turma, os alunos são carregados automaticamente
3. **Validações**: Todos os campos obrigatórios são validados antes de enviar
4. **Toast de Sucesso**: Mensagem verde confirmando o registro da ocorrência
5. **Responsivo**: Funciona bem em desktop e mobile

---

## 📞 Próximas Funcionalidades Sugeridas:

1. Criar página de relatórios de ocorrências
2. Adicionar filtros por aluno/turma/data
3. Gerar PDF com histórico de ocorrências
4. Notificações aos responsáveis (email)
5. Dashboard de estatísticas de ocorrências por tipo
