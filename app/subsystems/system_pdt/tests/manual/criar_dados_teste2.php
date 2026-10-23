<?php
/**
 * Script para criar dados de teste
 */
session_start();

require_once __DIR__ . '/../../app/main/config/connect.php';
require_once __DIR__ . '/../../app/main/models/aluno_model.php';
require_once __DIR__ . '/../../app/main/models/usuario_model.php';

$model_aluno = new AlunoModel();

// Criar alunos de teste
$alunos_teste = [
    ['nome' => 'João Silva', 'matricula' => '0000001', 'email' => 'joao@test.com', 'pcd' => 'nao', 'turma_id' => 1],
    ['nome' => 'Maria Santos', 'matricula' => '0000002', 'email' => 'maria@test.com', 'pcd' => 'nao', 'turma_id' => 1],
    ['nome' => 'Pedro Oliveira', 'matricula' => '0000003', 'email' => 'pedro@test.com', 'pcd' => 'sim', 'turma_id' => 1],
    ['nome' => 'Ana Costa', 'matricula' => '0000004', 'email' => 'ana@test.com', 'pcd' => 'nao', 'turma_id' => 1],
    ['nome' => 'Carlos Mendes', 'matricula' => '0000005', 'email' => 'carlos@test.com', 'pcd' => 'nao', 'turma_id' => 1],
];

echo "<h1>Criando Dados de Teste</h1>";

foreach ($alunos_teste as $aluno) {
    $result = $model_aluno->cadastrarAluno(
        $aluno['nome'],
        $aluno['matricula'],
        $aluno['email'],
        $aluno['pcd'],
        0,
        $aluno['turma_id']
    );
    
    if (is_array($result) && $result['success']) {
        echo "✅ Aluno criado: " . $aluno['nome'] . " (ID: " . $result['id'] . ")<br>";
    } else if ($result) {
        echo "✅ Aluno criado: " . $aluno['nome'] . " (ID: " . $result . ")<br>";
    } else {
        echo "❌ Erro ao criar: " . $aluno['nome'] . "<br>";
    }
}

echo "<hr>";
echo "<p><a href='teste_diagnostico.php'>Voltar ao Diagnóstico</a></p>";
?>
