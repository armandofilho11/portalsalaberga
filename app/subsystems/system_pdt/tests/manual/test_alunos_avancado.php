<?php
/**
 * Teste Avançado de Registro de Alunos
 * Simula o fluxo completo: Entrar em turma → Registrar aluno → Listar alunos
 */

require_once __DIR__ . '/../../app/main/models/aluno_model.php';
require_once __DIR__ . '/../../app/main/models/turmas_model.php';

echo "\n=== TESTE AVANÇADO DE REGISTRO DE ALUNOS ===\n\n";

// 1. Obter turmas do professor (simulando login)
echo "1️⃣  Obtendo turmas...\n";
$turmasModel = new TurmasModel();
$turmas = $turmasModel->listarTurmas();

if (empty($turmas)) {
    echo "  ❌ Nenhuma turma encontrada!\n";
    echo "  ℹ️  Crie uma turma primeiro\n";
    exit;
}

$turmaSelecionada = $turmas[0];
$turmaId = $turmaSelecionada['id_turma'];
echo "  ✅ Turma selecionada: ID {$turmaId} ({$turmaSelecionada['serie_turma']}º {$turmaSelecionada['nome_turma']})\n";

// 2. Listar alunos antes do cadastro
echo "\n2️⃣  Alunos antes do cadastro:\n";
$alunosAntes = (new AlunoModel())->listarPorTurma($turmaId);
echo "  Total: " . count($alunosAntes) . " aluno(s)\n";
if (!empty($alunosAntes)) {
    foreach (array_slice($alunosAntes, 0, 3) as $a) {
        echo "    - {$a['nome']} ({$a['matricula']})\n";
    }
    if (count($alunosAntes) > 3) {
        echo "    ... e " . (count($alunosAntes) - 3) . " outro(s)\n";
    }
}

// 3. Registrar novo aluno
echo "\n3️⃣  Registrando novo aluno...\n";
$timestamp = time();
$novosAlunos = [
    [
        'nome' => "Teste Aluno 1 - {$timestamp}",
        'matricula' => str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
        'email' => "teste1_{$timestamp}@example.com",
        'pcd' => 'nao',
        'rendimento' => 7.5
    ],
    [
        'nome' => "Teste Aluno 2 - {$timestamp}",
        'matricula' => str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
        'email' => "teste2_{$timestamp}@example.com",
        'pcd' => 'sim',
        'rendimento' => 8.0
    ]
];

$alunoModel = new AlunoModel();
$idsInseridos = [];

foreach ($novosAlunos as $aluno) {
    $resultado = $alunoModel->cadastrarAluno(
        $aluno['nome'],
        $aluno['matricula'],
        $aluno['email'],
        $aluno['pcd'],
        $aluno['rendimento'],
        $turmaId  // ← ESTE É O PARÂMETRO IMPORTANTE
    );

    if (!empty($resultado['success'])) {
        echo "  ✅ Aluno '{$aluno['nome']}' registrado (ID: {$resultado['id']})\n";
        echo "     - Matrícula: {$aluno['matricula']}\n";
        echo "     - Email: {$aluno['email']}\n";
        echo "     - PCD: {$aluno['pcd']}\n";
        echo "     - Rendimento: {$aluno['rendimento']}\n";
        $idsInseridos[] = $resultado['id'];
    } else {
        echo "  ❌ Erro ao registrar '{$aluno['nome']}': {$resultado['error']}\n";
    }
}

// 4. Listar alunos após cadastro
echo "\n4️⃣  Alunos após cadastro:\n";
$alunosDepois = $alunoModel->listarPorTurma($turmaId);
echo "  Total: " . count($alunosDepois) . " aluno(s) (antes tinha " . count($alunosAntes) . ")\n";

if (count($alunosDepois) > count($alunosAntes)) {
    echo "  ✅ SUCESSO! Novos alunos foram adicionados!\n";
    echo "  Últimos alunos registrados:\n";
    foreach (array_slice($alunosDepois, 0, 3) as $a) {
        echo "    - {$a['nome']} ({$a['matricula']}) - Turma ID: {$a['id_turma']}\n";
    }
} else {
    echo "  ⚠️  Nenhum novo aluno foi adicionado!\n";
}

// 5. Validar integridade dos dados
echo "\n5️⃣  Validação de integridade:\n";
$testeErro = false;

// Testar duplicação de email
echo "  Teste 1: Registrar aluno com email duplicado...\n";
$resultado = $alunoModel->cadastrarAluno(
    "Teste Duplicado",
    str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
    $novosAlunos[0]['email'],  // ← Email já existe
    'nao',
    0,
    $turmaId
);
if (empty($resultado['success']) && strpos($resultado['error'], 'Email') !== false) {
    echo "    ✅ Validação funcionando: " . $resultado['error'] . "\n";
} else {
    echo "    ❌ Validação FALHOU!\n";
    $testeErro = true;
}

// Testar duplicação de matrícula
echo "  Teste 2: Registrar aluno com matrícula duplicada...\n";
$resultado = $alunoModel->cadastrarAluno(
    "Teste Duplicado 2",
    $novosAlunos[0]['matricula'],  // ← Matrícula já existe
    "novomail_{$timestamp}@example.com",
    'nao',
    0,
    $turmaId
);
if (empty($resultado['success']) && strpos($resultado['error'], 'Matrícula') !== false) {
    echo "    ✅ Validação funcionando: " . $resultado['error'] . "\n";
} else {
    echo "    ❌ Validação FALHOU!\n";
    $testeErro = true;
}

// 6. Resumo Final
echo "\n6️⃣  RESUMO FINAL:\n";
if (!$testeErro && count($alunosDepois) > count($alunosAntes)) {
    echo "  🎉 TODOS OS TESTES PASSARAM!\n";
    echo "  ✅ Alunos estão sendo registrados no banco\n";
    echo "  ✅ Validações de constraint estão funcionando\n";
    echo "  ✅ Turma está sendo associada corretamente\n";
    echo "\n  Próximo passo: Testar via interface web\n";
} else {
    echo "  ⚠️  Alguns testes falharam. Verifique os logs acima.\n";
}

echo "\n=== FIM DO TESTE ===\n\n";
?>
