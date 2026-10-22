<?php
require_once(__DIR__ . '/../config/connect.php');

class select_model extends connect
{

    //atrivbutos
    function __construct()
    {
        parent::__construct();
    }

    function total_empresa()
    {
        $stmt_empresa = $this->connect->query("SELECT count(*) FROM concedentes");
        $result = $stmt_empresa->fetch(PDO::FETCH_ASSOC);

        return $result;
    }
    function total_vagas()
    {
        $stmt_vagas = $this->connect->query("SELECT sum(quant_vaga) as quant_vaga FROM vagas");
        $result = $stmt_vagas->fetch(PDO::FETCH_ASSOC);

        return $result;
    }
    function total_vagas_des()
    {
        $stmt_empresa = $this->connect->query("SELECT sum(quant_vaga) as quant_vaga FROM vagas WHERE id_perfil = 2");
        $result = $stmt_empresa->fetch(PDO::FETCH_ASSOC);

        return $result;
    }
    function total_vagas_dev()
    {
        $stmt_empresa = $this->connect->query("SELECT sum(quant_vaga) as quant_vaga FROM vagas WHERE id_perfil = 1");
        $result = $stmt_empresa->fetch(PDO::FETCH_ASSOC);

        return $result;
    }
    function total_vagas_tut()
    {
        $stmt_empresa = $this->connect->query("SELECT sum(quant_vaga) as quant_vaga FROM vagas WHERE id_perfil = 4");
        $result = $stmt_empresa->fetch(PDO::FETCH_ASSOC);

        return $result;
    }
    function total_vagas_sup()
    {
        $stmt_empresa = $this->connect->query("SELECT sum(quant_vaga) from vagas where id_perfil = 3; ");
        $result = $stmt_empresa->fetch(PDO::FETCH_ASSOC);

        return $result;
    }
    function estagios_ativas()
    {

        $stmt_ativas = $this->connect->query("SELECT count(*) FROM selecionado");
        $result =  $stmt_ativas->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    function concedentes($search = '', $area = '')
    {
        $sql = "SELECT DISTINCT c.*, 
                GROUP_CONCAT(DISTINCT p.nome_perfil) as areas
                FROM concedentes c
                LEFT JOIN vagas v ON c.id = v.id_concedente
                LEFT JOIN perfis p ON v.id_perfil = p.id
                WHERE 1=1";

        $params = [];

        if (!empty($search)) {
            $sql .= " AND (c.nome LIKE ? OR c.endereco LIKE ? OR c.contato LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($area)) {
            $sql .= " AND p.nome_perfil = ?";
            $params[] = $area;
        }

        $sql .= " GROUP BY c.id ORDER BY c.nome ASC";

        $stmt = $this->connect->prepare($sql);

        if (!empty($params)) {
            $stmt->execute($params);
        } else {
            $stmt->execute();
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    private function removeAcentos($string)
    {
        $string = str_replace(
            ['á', 'à', 'ã', 'â', 'é', 'ê', 'í', 'ó', 'ô', 'õ', 'ú', 'ü', 'ç', 'Á', 'À', 'Ã', 'Â', 'É', 'Ê', 'Í', 'Ó', 'Ô', 'Õ', 'Ú', 'Ü', 'Ç'],
            ['a', 'a', 'a', 'a', 'e', 'e', 'i', 'o', 'o', 'o', 'u', 'u', 'c', 'A', 'A', 'A', 'A', 'E', 'E', 'I', 'O', 'O', 'O', 'U', 'U', 'C'],
            $string
        );
        return $string;
    }

    function alunos($nome_perfil = 0, $search = '', $filtro = '')
    {
        // Normalizar o nome do perfil
        if (
            strtolower($nome_perfil) === 'design/mídias' ||
            strtolower($nome_perfil) === 'design/mídia' ||
            strtolower($nome_perfil) === 'design/social mídia' ||
            strtolower($nome_perfil) === 'design'
        ) {
            $nome_perfil = 'Design/Mídia';
        }

        $sql = "SELECT 
                    a.id,
                    a.nome,
                    a.medias,
                    a.projetos,
                    a.ocorrencia,
                    a.entregas_individuais,
                    a.entregas_grupo,
                    a.perfil_opc1,
                    a.perfil_opc2,
                    a.custeio,
                    (
                        a.medias + 
                        (CASE WHEN a.projetos != '' THEN 5 ELSE 0 END) -
                        (a.ocorrencia * 0.5) +
                        (a.entregas_individuais * 5) +
                        (a.entregas_grupo * 5)
                    ) AS score,
                    CASE 
                        WHEN a.perfil_opc1 = :nome_perfil THEN 1
                        WHEN a.perfil_opc2 = :nome_perfil THEN 2
                        ELSE 3
                    END AS priority_group
                FROM aluno a
                WHERE NOT EXISTS (
                    SELECT 1 FROM selecao se
                    WHERE se.id_aluno = a.id AND (se.Status = '0' OR se.Status = '1')
                )
                ORDER BY 
                    priority_group ASC,
                    score DESC,
                    a.medias DESC,
                    COALESCE(a.ocorrencia, 0) ASC";
        $stmt = $this->connect->prepare($sql);
        $stmt->bindValue(':nome_perfil', $nome_perfil, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Log para debug
        error_log("Total de alunos retornados: " . count($result));
        error_log("Query SQL: " . $sql);
        error_log("Nome do perfil: " . $nome_perfil);
        
        return $result;
    }
    function alunos_aptos_curso($nome_perfil = 0, $search = '', $filtro = '')
    {
        $sql = "SELECT 
                    a.id,
                    a.nome,
                    a.contato,
                    a.medias,
                    a.email,
                    a.projetos,
                    a.perfil_opc1,
                    a.perfil_opc2,
                    a.ocorrencia,
                    a.custeio,
                    a.entregas_individuais,
                    a.entregas_grupo
                FROM aluno a
                WHERE 1=1";

        $params = [];

        if (!empty($search)) {
            $sql .= " AND (a.nome LIKE ? OR a.email LIKE ? OR a.contato LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($nome_perfil)) {
            $sql .= " AND (a.perfil_opc1 = ? OR a.perfil_opc2 = ?)";
            $params[] = $nome_perfil;
            $params[] = $nome_perfil;
        }

        $sql .= " ORDER BY a.nome ASC";

        $stmt = $this->connect->prepare($sql);
        
        if (!empty($params)) {
            $stmt->execute($params);
        } else {
            $stmt->execute();
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function alunos_aptos($nome_perfil = 0, $search = '', $filtro = '')
    {
        $sql = "SELECT 
                    a.id, 
                    a.nome, 
                    a.perfil_opc1, 
                    c.nome AS empresa,
                    p.nome_perfil AS perfil_empresa,
                    'approved' as status
                FROM selecionado s
                INNER JOIN aluno a ON s.id_aluno = a.id
                INNER JOIN vagas v ON s.id_vaga = v.id
                INNER JOIN concedentes c ON v.id_concedente = c.id
                INNER JOIN perfis p ON v.id_perfil = p.id
                
                UNION
                
                SELECT 
                    a.id, 
                    a.nome, 
                    a.perfil_opc1, 
                    c.nome AS empresa,
                    p.nome_perfil AS perfil_empresa,
                    'waiting' as status
                FROM selecao se
                INNER JOIN aluno a ON se.id_aluno = a.id
                INNER JOIN vagas v ON se.id_vaga = v.id
                INNER JOIN concedentes c ON v.id_concedente = c.id
                INNER JOIN perfis p ON v.id_perfil = p.id
                LEFT JOIN selecionado s ON se.id_aluno = s.id_aluno AND se.id_vaga = s.id_vaga
                WHERE s.id_aluno IS NULL
                
                UNION
                
                SELECT 
                    a.id, 
                    a.nome, 
                    a.perfil_opc1, 
                    NULL AS empresa,
                    NULL AS perfil_empresa,
                    'no_interview' as status
                FROM aluno a
                LEFT JOIN selecao se ON a.id = se.id_aluno
                LEFT JOIN selecionado s ON a.id = s.id_aluno
                WHERE se.id_aluno IS NULL AND s.id_aluno IS NULL
                
                ORDER BY nome ASC";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function vagas($search = '', $area = '', $empresa = '')
    {
        $sql = "SELECT 
                v.id as id,
                c.nome AS nome_empresa,
                p.nome_perfil AS nome_perfil,
                v.quant_vaga AS quant_vaga,
                v.quant_cand AS quant_cand,
                v.data as data,
                v.hora as hora,
                v.tipo_vaga as tipo_vaga,
                v.id_concedente as id_empresa,
                v.id_perfil as id_perfil
            FROM 
                vagas v
            INNER JOIN concedentes c ON v.id_concedente = c.id
            INNER JOIN perfis p ON v.id_perfil = p.id
            WHERE 1=1";

        $params = [];

        if (!empty($search)) {
            $sql .= " AND (c.nome LIKE ? OR p.nome_perfil LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($area)) {
            $sql .= " AND v.id_perfil = ?";
            $perfil_id = null;
            switch (strtolower($area)) {
                case '1':
                    $perfil_id = 1;
                    break;
                case '2':

                    $perfil_id = 2;
                    break;
                case '3':
                    $perfil_id = 3;
                    break;
                case '4':
                    $perfil_id = 4;
                    break;
            }
            if ($perfil_id !== null) {
                $params[] = $perfil_id;
            } else {
                $sql .= " AND 1=0";
            }
        }

        if (!empty($empresa)) {
            $sql .= " AND v.id_concedente = ?";
            $params[] = $empresa;
        }

        $sql .= " ORDER BY v.data DESC, v.hora DESC";

        $stmt = $this->connect->prepare($sql);

        if (!empty($params)) {
            $stmt->execute($params);
        } else {
            $stmt->execute();
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function alunos_selecionados_relatorio($id_vaga)
    {
        $stmt = $this->connect->prepare("
            SELECT a.nome, a.id, a.contato
            FROM aluno a
            INNER JOIN selecionado s ON a.id = s.id_aluno
            WHERE s.id_vaga = :id_vaga");
        $stmt->bindValue(':id_vaga', $id_vaga, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function alunos_espera($id_vaga)
    {
        $stmt = $this->connect->query("
            SELECT DISTINCT a.nome, a.id, a.contato 
            FROM aluno a
            INNER JOIN selecao s ON a.id = s.id_aluno
            LEFT JOIN selecionado sel ON a.id = sel.id_aluno AND sel.id_vaga = '$id_vaga'
            WHERE s.id_vaga = '$id_vaga' 
            AND sel.id_aluno IS NULL");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    function alunos_selecionados_estagio($id_vaga)
    {
        $sql = "
            SELECT a.nome, a.contato, a.id, 'approved' as status
            FROM aluno a
            INNER JOIN selecionado s ON a.id = s.id_aluno
            WHERE s.id_vaga = :id_vaga

            UNION

            SELECT a.nome, a.contato, a.id, 'waiting' as status
            FROM aluno a
            INNER JOIN selecao se ON a.id = se.id_aluno
            LEFT JOIN selecionado s ON a.id = s.id_aluno AND s.id_vaga = :id_vaga
            WHERE se.id_vaga = :id_vaga AND s.id_aluno IS NULL
        ";
        $stmt = $this->connect->prepare($sql);
        $stmt->bindValue(':id_vaga', $id_vaga, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function vagas_com_alunos()
    {
        $stmt = $this->connect->prepare("
            SELECT DISTINCT v.id as id_vaga 
            FROM vagas v
            INNER JOIN (
                SELECT id_vaga FROM selecao
                UNION
                SELECT id_vaga FROM selecionado
            ) as vagas_alunos ON v.id = vagas_alunos.id_vaga
            ORDER BY v.data DESC, v.hora DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function nome_empresa_por_vaga($id_vaga)
    {
        $stmt = $this->connect->prepare("SELECT c.nome FROM vagas v INNER JOIN concedentes c ON v.id_concedente = c.id WHERE v.id = :id_vaga LIMIT 1");
        $stmt->bindValue(':id_vaga', $id_vaga, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['nome'] : null;
    }

    function aprovar_selecionados($selecionados)
    {
        $count = 0;
        foreach ($selecionados as $item) {
            // Atualiza o status do aluno aprovado para '1' na tabela selecao
            $updateAprovado = $this->connect->prepare("UPDATE selecao SET Status = '1' WHERE id_aluno = :id_aluno AND id_vaga = :id_vaga");
            $updateAprovado->execute([
                ':id_aluno' => $item['id_aluno'],
                ':id_vaga' => $item['id_vaga']
            ]);

            // Atualiza o status dos demais alunos da mesma vaga para '2'
            $updateNaoSelecionados = $this->connect->prepare("UPDATE selecao SET Status = '2' WHERE id_vaga = :id_vaga AND id_aluno != :id_aluno AND Status = '0'");
            $updateNaoSelecionados->execute([
                ':id_vaga' => $item['id_vaga'],
                ':id_aluno' => $item['id_aluno']
            ]);

            // Verifica se já existe na tabela selecionado
            $check = $this->connect->prepare("SELECT 1 FROM selecionado WHERE id_aluno = :id_aluno AND id_vaga = :id_vaga");
            $check->execute([
                ':id_aluno' => $item['id_aluno'],
                ':id_vaga' => $item['id_vaga']
            ]);

            if ($check->fetch()) {
                continue; // Já existe, não insere
            }

            // Busca o nome do aluno
            $stmt_aluno = $this->connect->prepare("SELECT nome FROM aluno WHERE id = :id_aluno");
            $stmt_aluno->execute([':id_aluno' => $item['id_aluno']]);
            $aluno = $stmt_aluno->fetch(PDO::FETCH_ASSOC);

            $stmt = $this->connect->prepare("INSERT INTO selecionado (id_aluno, id_vaga, nome) VALUES (:id_aluno, :id_vaga, :nome)");
            if ($stmt->execute([
                ':id_aluno' => $item['id_aluno'],
                ':id_vaga' => $item['id_vaga'],
                ':nome' => $aluno['nome']
            ])) {
                $count++;
            }
        }
        return $count;
    }
    public function getConnection()
    {
        return $this->connect;
    }

    public function total_alunos()
    {

        $stmt_alunos = $this->connect->query('SELECT count(*) FROM aluno');
        $result = $stmt_alunos->fetch(PDO::FETCH_ASSOC);
        foreach ($result as $dado) {
            return $dado;
        }
    }

    function vaga_por_id($id_vaga)
    {
        $stmt = $this->connect->prepare("SELECT * FROM vagas WHERE id = ? LIMIT 1");
        $stmt->execute([$id_vaga]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function concedente_por_id($empresa_id)
    {
        $sql = "SELECT id, nome, nome_contato, contato, endereco FROM concedentes WHERE id = :empresa_id LIMIT 1";
        $stmt = $this->connect->prepare($sql);
        $stmt->bindParam(':empresa_id', $empresa_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function alunos_por_perfil()
    {
        $sql = "SELECT 
            a.id,
            a.nome,
            a.medias,
            a.projetos,
            a.ocorrencia,
            a.entregas_individuais,
            a.entregas_grupo,
            a.perfil_opc1,
            a.perfil_opc2,
            a.custeio,
            (
                a.medias + 
                (CASE WHEN a.projetos != '' THEN 5 ELSE 0 END) -
                (a.ocorrencia * 0.5) +
                (a.entregas_individuais * 5) +
                (a.entregas_grupo * 5)
            ) AS score
        FROM aluno a
        ORDER BY 
            score DESC,
            a.medias DESC";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getAlunosByEquipe($id_equipe)
    {
        try {
            // Conexão direta com o banco de dados parcial_alunos
            $conexao = new PDO("mysql:host=localhost;dbname=parcial_alunos", "root", "");
            $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepara e executa a consulta para verificar os alunos na tabela 'aluno' pela equipe
            $querySelectAlunos = "SELECT id, nome FROM aluno WHERE equipe = :id_equipe";
            $stmtSelectAlunos = $conexao->prepare($querySelectAlunos);
            $stmtSelectAlunos->bindParam(':id_equipe', $id_equipe);
            $stmtSelectAlunos->execute();
            $alunos = $stmtSelectAlunos->fetchAll(PDO::FETCH_ASSOC);

            return $alunos;

        } catch (PDOException $e) {
            error_log("Erro no banco de dados ao buscar alunos por equipe: " . $e->getMessage());
            return []; // Retorna um array vazio em caso de erro
        }
    }
}
