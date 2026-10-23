# Análise Técnica - System PDT Frequência e Registro de Alunos

## Status Atual da Interface

### ✅ Confirmado Funcionando
- Autenticação de professor
- Carregamento da lista de alunos (5 alunos exibidos corretamente)
- Visualização de turmas
- Navegação básica do painel
- Campos de input estão funcionais

### ⚠️ Problemas Identificados

## Problema 1: Registrar Aluno - Logout ao Clicar "Registrar"

**Sintomas:**
- Modal abre normalmente
- Formulário pode ser preenchido (nome, matrícula, email, PCD)
- Clique em "Registrar" redireciona para tela de login

**Causa Provável:**
- Erro 422/403/401 retornado pelo backend não tratado
- Matrícula sendo validada de forma muito rígida
- Bug na manipulação de resposta do fetch

**Arquivo Afetado:**
- `app/main/views/professor.php` - função `salvarNovoAluno()` (procurar por id ou function name)
- `app/main/controllers/aluno_controller.php` - ação `cadastrar_aluno`

**Recomendação:**
```javascript
// Adicionar try-catch e validação melhor:
fetch('../controllers/aluno_controller.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(...)
})
.then(r => {
    if (!r.ok) {
        console.error('Erro HTTP:', r.status);
        mostrarToast('Erro ao registrar: ' + r.statusText);
        return;
    }
    return r.json();
})
.then(data => {
    if (!data.sucesso) {
        mostrarToast('Erro: ' + (data.erro || 'Desconhecido'));
        return;
    }
    // sucesso
})
.catch(err => {
    console.error('Erro:', err);
    mostrarToast('Erro de conexão: ' + err.message);
});
```

## Problema 2: Realizar Frequência - Sem Resposta ao Clique

**Sintomas:**
- Botão "Realizar frequência" existe e é clicável
- Nenhuma ação visual acontece ao clicar
- Nenhuma mudança na lista de alunos
- Console deveria mostrar logs da função

**Causa Provável:**
- Validação falhando silenciosamente (linha 2214-2221)
  - `dataFrequenciaInput.value` talvez não esteja preenchido corretamente
  - `getTurmaIdModal()?.value` retornando null
- Função `carregarFrequenciaTurma()` falhando

**Arquivos Afetados:**
- `app/main/views/professor.php` - funções:
  - `abrirRealizarFrequencia()` (linha ~2211)
  - `carregarFrequenciaTurma()` (linha ~2174)
  - `getTurmaIdModal()` 
- `app/main/controllers/frequencia_controller.php` - ação `listar_frequencia`

**Debug Recomendado:**
```javascript
// Adicionar logs na função abrirRealizarFrequencia:
async function abrirRealizarFrequencia(){
    console.log('1. Data:', dataFrequenciaInput?.value);
    if (!dataFrequenciaInput.value) {
        console.error('Erro: Data não selecionada');
        alert('Selecione a data da frequência primeiro');
        return;
    }
    
    const turmaInput = getTurmaIdModal();
    console.log('2. Turma Element:', turmaInput);
    console.log('3. Turma Value:', turmaInput?.value);
    
    if (!turmaInput?.value) {
        console.error('Erro: Turma inválida');
        alert('Turma inválida');
        return;
    }
    
    // ... resto do código ...
}
```

## Problema 3: Escopo de Variáveis Globais

**Variáveis Afetadas:**
- `alunosModalCache` (linha 1716)
- `frequenciaRegistros` (linha 1697)
- Outras variáveis de estado

**Problema:**
- Declaradas com `let` (escopo local do módulo)
- Não acessíveis via `window.varName` de fora do arquivo
- Dificultam debugging e testes automatizados

**Recomendação:**
```javascript
// Mudar de:
let alunosModalCache = [];

// Para:
window.alunosModalCache = [];  // ou atribuir a um objeto global
window.frequenciaRegistros = {};
```

---

## Recomendações para Teste Manual

1. Abrir DevTools (F12) no navegador
2. Ir para aba Console
3. Clicar em "Realizar frequência"
4. Analisar mensagens de erro ou logs
5. Verificar requisição Network (aba Network) para ver resposta da API

## Próximas Ações

1. **Curto Prazo**: Testar manualmente via navegador com DevTools aberto
2. **Médio Prazo**: Adicionar logs console em funções críticas
3. **Longo Prazo**: Refatorar variáveis globais e mecanismo de estado
