# 🧪 Guia de Teste: Registro de Alunos

## ✅ Verificação Rápida via CLI

Antes de testar pela interface web, verifique se o backend está funcionando:

```bash
cd c:\xampp\htdocs\portalsalaberga\app\subsystems\system_pdt
php tests\manual\test_alunos.php
```

**Resultado esperado**: ✅ em todas as seções

---

## 🌐 Teste Completo via Interface Web

### Pré-requisitos
- ✅ MySQL/MariaDB rodando
- ✅ Apache/XAMPP rodando
- ✅ Banco `sistema_pdt` criado
- ✅ Pelo menos 1 turma criada
- ✅ Professor logado

### Passos do Teste

1. **Acesse a interface**
   ```
   http://localhost/portalsalaberga/app/subsystems/system_pdt/app/main/index.php
   ```

2. **Faça login como professor**
   - Usuário: seu_email@example.com
   - Senha: sua_senha

3. **Navegue para "Minhas turmas"**
   - Clique no menu lateral esquerdo
   - Selecione "Minhas turma"

4. **Selecione uma turma**
   - Clique no botão "Entrar" da turma desejada

5. **Abra o modal de registro de alunos**
   - Procure pelo botão "Gerenciar alunos" ou similar
   - Clique para abrir o modal

6. **Preencha o formulário**
   ```
   Nome: João Silva
   Matrícula: 1234567
   Email: joao.silva@example.com
   PCD: Não
   ```

7. **Clique em "Salvar"**
   - Esperado: Toast verde com mensagem "✅ Aluno registrado com sucesso!"
   - O aluno deve aparecer na lista imediatamente

---

## 🔍 Validações que Devem Ocorrer

### ✅ Validações Obrigatórias
- [ ] Nome vazio → Alerta: "Preencha o nome do aluno"
- [ ] Matrícula < 7 dígitos → Alerta: "Matrícula deve ter exatamente 7 dígitos"
- [ ] Email inválido → Alerta: "Preencha o email do aluno"
- [ ] Email duplicado → Mensagem: "Email já está registrado no sistema"
- [ ] Matrícula duplicada → Mensagem: "Matrícula já existe no sistema"
- [ ] Turma não selecionada → Alerta: "Turma não foi selecionada"

### 🔴 Erros Esperados
Esses erros PODEM aparecer (confirme depois):
- "Turma não encontrada no sistema" → Turma foi deletada
- "Turma não existe ou foi removida" → Foreign key error

---

## 🐛 Troubleshooting

### Problema: "Erro ao registrar: Erro desconhecido"

**Solução 1**: Verifique o Console do Navegador (F12)
```javascript
// Procure por mensagens vermelhas que expliquem melhor o erro
// Exemplo: "Matrícula já existe no sistema"
```

**Solução 2**: Verifique o error_log do PHP
```bash
cd C:\xampp\apache\logs
type error.log
```

**Solução 3**: Teste via CLI
```bash
php tests\manual\test_alunos.php
```

---

### Problema: "Erro de conexão"

**Verificações**:
1. MySQL está rodando? (`Ctrl+Alt+Del` → Processos → mysqld)
2. Apache está rodando? (Verificar XAMPP Control Panel)
3. Banco `sistema_pdt` existe? (`CREATE DATABASE IF NOT EXISTS sistema_pdt;`)

---

### Problema: Aluno salva mas não aparece na lista

**Causa possível**: Função `carregarAlunosTurma()` falhou

**Teste**:
```javascript
// No console do navegador (F12), execute:
carregarAlunosTurma(1)  // 1 = ID da turma
```

**Solução**: Recarregue a página (F5)

---

## 📝 Dados de Teste Válidos

```
Nome: Aluno Teste 1
Matrícula: 1234567
Email: teste1@example.com
PCD: Não
Rendimento: 0

---

Nome: João da Silva
Matrícula: 2345678
Email: joao@example.com
PCD: Sim
Rendimento: 7.5

---

Nome: Maria Santos
Matrícula: 3456789
Email: maria@example.com
PCD: Não
Rendimento: 8.0
```

---

## ✅ Checklist Final

- [ ] CLI test passou (`php tests\manual\test_alunos.php`)
- [ ] Conseguiu fazer login
- [ ] Conseguiu entrar em uma turma
- [ ] Modal de registro abriu
- [ ] Formulário aceitou dados válidos
- [ ] Toast de sucesso apareceu
- [ ] Aluno apareceu na lista
- [ ] Conseguiu registrar 3+ alunos diferentes
- [ ] Validações (email duplicado, matrícula inválida) funcionaram

---

## 📞 Se Ainda Houver Problemas

1. Envie os seguintes arquivos:
   - `C:\xampp\apache\logs\error.log`
   - Output de `php tests\manual\test_alunos.php`
   - Screenshot do erro

2. Execute em terminal:
   ```bash
   php -v
   mysql -u root -p -e "SELECT VERSION();"
   ```

3. Verifique se as tabelas existem:
   ```bash
   mysql -u root -e "USE sistema_pdt; SHOW TABLES;"
   ```

---

## 🎉 Próximas Funcionalidades

Após confirmar que o registro funciona:
- [ ] Teste edição de alunos
- [ ] Teste exclusão de alunos
- [ ] Teste lançamento de notas
- [ ] Teste registro de ocorrências
