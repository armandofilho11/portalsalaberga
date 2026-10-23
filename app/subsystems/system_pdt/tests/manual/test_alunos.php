<?php
/**
 * Arquivo de teste para registro de alunos
 * Debug e validação do fluxo completo
 */

require_once __DIR__ . '/../../app/main/models/aluno_model.php';
require_once __DIR__ . '/../../app/main/models/turmas_model.php';

echo "=== TESTE DE REGISTRO DE ALUNOS ===\n\n";

// 1. Teste de conexão básica
echo "1️⃣  Testando conexão com o banco de dados:\n";
try {
    $turmasModel = new TurmasModel();
    echo "  ✅ Conexão bem-sucedida\n";
} catch (Exception $e) {
    echo "  ❌ Erro de conexão: {$e->getMessage()}\n";
    exit;
}

// 2. Listar turmas disponíveis
echo "\n2️⃣  Turmas disponíveis:\n";
try {
    $turmasModel = new TurmasModel();
    $turmas = $turmasModel->listarTurmas();
    if (empty($turmas)) {
        echo "  ❌ Nenhuma turma encontrada no banco!\n";
        echo "  ℹ️  Crie uma turma primeiro através da interface\n";
    } else {
        echo "  Encontradas " . count($turmas) . " turma(s):\n";
        foreach (array_slice($turmas, 0, 5) as $t) {
            echo "  - ID {$t['id_turma']}: {$t['serie_turma']}º {$t['nome_turma']} (Sala: {$t['sala_turma']})\n";
        }
    }
} catch (Exception $e) {
    echo "  ❌ Erro: {$e->getMessage()}\n";
    exit;
}

// 3. Testar cadastro de aluno
echo "\n3️⃣  Teste de cadastro de aluno:\n";
if (!empty($turmas)) {
    $turma_teste = $turmas[0]['id_turma'];
    $nome_teste = "Aluno Teste " . date('YmdHis');
    $matricula_teste = str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT);
    $email_teste = "teste_" . time() . "@example.com";

    echo "  Dados a registrar:\n";
    echo "    - Nome: {$nome_teste}\n";
    echo "    - Matrícula: {$matricula_teste}\n";
    echo "    - Email: {$email_teste}\n";
    echo "    - Turma ID: {$turma_teste}\n";

    $modelAluno = new AlunoModel();
    $resultado = $modelAluno->cadastrarAluno($nome_teste, $matricula_teste, $email_teste, 'nao', 0, $turma_teste);

    if (!empty($resultado['success'])) {
        echo "  ✅ Aluno cadastrado com sucesso! ID: {$resultado['id']}\n";
    } else {
        echo "  ❌ Erro ao cadastrar: {$resultado['error']}\n";
    }
} else {
    echo "  ⚠️  Não é possível testar cadastro sem turmas\n";
}

// 4. Listar alunos da primeira turma
echo "\n4️⃣  Alunos da primeira turma:\n";
if (!empty($turmas)) {
    $turma_id = $turmas[0]['id_turma'];
    $modelAluno = new AlunoModel();
    $alunos = $modelAluno->listarPorTurma($turma_id);
    if (empty($alunos)) {
        echo "  ℹ️  Nenhum aluno nesta turma\n";
    } else {
        echo "  Encontrados " . count($alunos) . " aluno(s):\n";
        foreach ($alunos as $a) {
            echo "  - {$a['nome']} ({$a['matricula']}) - {$a['email']}\n";
        }
    }
}

echo "\n=== FIM DO TESTE ===\n";
?>
