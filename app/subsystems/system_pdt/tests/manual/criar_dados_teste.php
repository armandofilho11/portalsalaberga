<?php
require_once __DIR__ . '/../../app/main/config/connect.php';
$db = new connect();
$pdo = $db->getConnection();

// ID do professor de teste que acabei de criar
$professor_id = $pdo->query("SELECT id FROM usuarios WHERE email = 'teste.professor@gmail.com'")->fetch(PDO::FETCH_ASSOC)['id'];

// Criar curso de teste (se não existir)
$curso = $pdo->query("SELECT id_curso FROM cursos LIMIT 1")->fetch(PDO::FETCH_ASSOC);
if (!$curso) {
    $stmt = $pdo->prepare('INSERT INTO cursos (nome_curso, descricao) VALUES (?, ?)');
    $stmt->execute(['Ensino Médio Teste', 'Curso de teste']);
    $curso_id = $pdo->lastInsertId();
    echo "✅ Curso criado: ID $curso_id\n";
} else {
    $curso_id = $curso['id_curso'];
    echo "ℹ️ Usando curso existente: ID $curso_id\n";
}

// Criar turma de teste
$stmt = $pdo->prepare('INSERT INTO turmas (nome_turma, serie_turma, id_professor, id_curso) VALUES (?, ?, ?, ?)');
$result = $stmt->execute(['Turma A Teste', '1º ano', $professor_id, $curso_id]);
$turma_id = $pdo->lastInsertId();
echo "✅ Turma criada: ID $turma_id\n";

// Criar alunos de teste
$nomes_alunos = ['João Silva', 'Maria Santos', 'Pedro Oliveira', 'Ana Costa', 'Carlos Mendes'];
$aluno_ids = [];
foreach ($nomes_alunos as $nome) {
    $stmt = $pdo->prepare('INSERT INTO alunos (nome, matricula, email, pcd, rendimento) VALUES (?, ?, ?, ?, ?)');
    $matricula = str_pad(count($aluno_ids) + 1, 7, '0', STR_PAD_LEFT);
    $email = strtolower(str_replace(' ', '.', $nome)) . '@aluno.local';
    $result = $stmt->execute([$nome, $matricula, $email, 'nao', 0]);
    $aluno_ids[] = $pdo->lastInsertId();
    echo "✅ Aluno criado: $nome (ID: " . $aluno_ids[-1] . ")\n";
}

// Criar notas para o bimestre 1 (médias variadas)
$notas_medias = [9.5, 5.5, 7.2, 8.1, 6.8];
foreach ($aluno_ids as $idx => $aluno_id) {
    $stmt = $pdo->prepare('INSERT INTO notas (id_aluno, id_turma, id_professor, bimestre, parcial, bimestral, somativa) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $media = $notas_medias[$idx];
    $result = $stmt->execute([$aluno_id, $turma_id, $professor_id, 1, $media * 0.3, $media * 0.7, $media]);
    echo "✅ Nota criada para aluno ID $aluno_id: $media\n";
}

echo "\n✅ Todos os dados de teste foram criados com sucesso!";
echo "\nProfessor ID: $professor_id";
echo "\nTurma ID: $turma_id";
echo "\nAlunos IDs: " . implode(', ', $aluno_ids);
?>
