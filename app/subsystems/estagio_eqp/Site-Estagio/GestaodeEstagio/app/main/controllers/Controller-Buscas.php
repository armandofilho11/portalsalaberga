<?php
header('Content-Type: application/json');

// Função para estabelecer conexão com o banco de dados
function getConnection() {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=u750204740_gestaoestagio', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erro de conexão: ' . $e->getMessage()]);
        exit;
    }
}

// Verificar a ação solicitada
if (!isset($_GET['action'])) {
    echo json_encode(['error' => 'Ação não especificada']);
    exit;
}

$action = $_GET['action'];
$pdo = getConnection();

switch ($action) {
    case 'get_alunos_empresa':
        // Busca alunos de uma empresa específica
        if (!isset($_GET['empresa_id'])) {
            echo json_encode(['error' => 'ID da empresa não fornecido']);
            exit;
        }

        try {
            $sql = 'SELECT a.nome, a.curso, s.hora, c.perfil
                    FROM aluno a
                    INNER JOIN selecao s ON a.id = s.id_aluno
                    INNER JOIN concedentes c ON s.id_concedente = c.id
                    WHERE s.id_concedente = :empresa_id
                    ORDER BY a.nome';

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':empresa_id', $_GET['empresa_id'], PDO::PARAM_INT);
            $stmt->execute();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erro ao buscar alunos: ' . $e->getMessage()]);
        }
        break;

    case 'get_alunos_suggestions':
        // Busca sugestões de alunos para autocomplete
        if (!isset($_GET['search'])) {
            echo json_encode(['error' => 'Termo de busca não fornecido']);
            exit;
        }

        try {
            $sql = 'SELECT id, nome, curso, matricula 
                    FROM aluno 
                    WHERE nome LIKE :search 
                    ORDER BY nome 
                    LIMIT 10';

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':search', '%' . $_GET['search'] . '%');
            $stmt->execute();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erro ao buscar alunos: ' . $e->getMessage()]);
        }
        break;

    case 'get_inscritos_processo':
        // Busca inscritos em um processo específico
        if (!isset($_GET['processo_id']) || empty($_GET['processo_id'])) {
            echo json_encode(['error' => 'ID do processo não fornecido']);
            exit;
        }

        try {
            // Primeiro, buscar o id_concedente do processo
            $sql_concedente = 'SELECT id_concedente FROM selecao WHERE id = :processo_id';
            $query_concedente = $pdo->prepare($sql_concedente);
            $query_concedente->bindValue(':processo_id', $_GET['processo_id'], PDO::PARAM_INT);
            $query_concedente->execute();
            $concedente = $query_concedente->fetch(PDO::FETCH_ASSOC);

            if (!$concedente) {
                echo json_encode(['error' => 'Processo não encontrado']);
                exit;
            }

            // Buscar todos os inscritos deste concedente
            $sql = 'SELECT s.id as id_selecao, a.id as id_aluno, a.nome, a.curso, 
                           s.hora, c.nome as nome_empresa, s.status, s.perfis_selecionados
                    FROM selecao s
                    INNER JOIN aluno a ON s.id_aluno = a.id
                    INNER JOIN concedentes c ON s.id_concedente = c.id
                    WHERE s.id_concedente = :id_concedente AND s.id_aluno IS NOT NULL
                    ORDER BY s.id DESC';

            $query = $pdo->prepare($sql);
            $query->bindValue(':id_concedente', $concedente['id_concedente'], PDO::PARAM_INT);
            $query->execute();
            $inscritos = $query->fetchAll(PDO::FETCH_ASSOC);

            // Converter os perfis selecionados de JSON para array
            foreach ($inscritos as &$inscrito) {
                if (!empty($inscrito['perfis_selecionados'])) {
                    $inscrito['perfis_selecionados'] = json_decode($inscrito['perfis_selecionados'], true);
                } else {
                    $inscrito['perfis_selecionados'] = [];
                }
            }

            echo json_encode($inscritos);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erro ao buscar inscritos: ' . $e->getMessage()]);
        }
        break;

    case 'get_processo_details':
        // Busca detalhes de um processo específico
        if (!isset($_GET['id'])) {
            echo json_encode(['error' => 'ID do processo não fornecido']);
            exit;
        }

        try {
            $sql = 'SELECT s.id, s.hora, s.local, c.id as id_concedente, c.nome as nome_empresa, c.perfis
                    FROM selecao s
                    INNER JOIN concedentes c ON s.id_concedente = c.id
                    WHERE s.id = :id';

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
            $stmt->execute();

            $processo = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$processo) {
                echo json_encode(['error' => 'Processo não encontrado']);
                exit;
            }

            // Converter a string de perfis em array e limpar espaços
            if (!empty($processo['perfis'])) {
                $processo['perfis'] = array_map('trim', explode(',', $processo['perfis']));
            } else {
                $processo['perfis'] = [];
            }

            echo json_encode($processo);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erro ao buscar detalhes do processo: ' . $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['error' => 'Ação inválida']);
        break;
}
?> 