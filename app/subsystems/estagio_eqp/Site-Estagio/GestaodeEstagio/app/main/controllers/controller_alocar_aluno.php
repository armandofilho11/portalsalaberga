<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_selecao = $_POST['id_selecao'];
    $id_aluno = $_POST['id_aluno'];

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=u750204740_gestaoestagio', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if student is already allocated in any selection
        $stmt = $pdo->prepare('SELECT id FROM selecao WHERE id_aluno = ? AND id != ? AND status = "alocado"');
        $stmt->execute([$id_aluno, $id_selecao]);
        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Aluno já está alocado em outro processo seletivo'
            ]);
            exit;
        }

        // Update the selecao table to allocate the student and mark as confirmed
        $stmt = $pdo->prepare('UPDATE selecao SET status = "alocado" WHERE id = ?');
        $result = $stmt->execute([$id_selecao]);

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Aluno alocado com sucesso'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao alocar aluno'
            ]);
        }

    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao alocar aluno: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método não permitido'
    ]);
}
?> 