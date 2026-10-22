<?php
require_once(__DIR__ . '/../config/connect.php');

/**
 * Camada de leitura do sistema.
 *
 * Reune consultas usadas por dashboards, telas de cadastro, filtros,
 * selecoes e relatorios. Metodos desta classe devem priorizar consultas
 * sem efeito colateral no banco.
 */
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

    function total_nao_selecionados()
    {
        $stmt_nao_selecionados = $this->connect->query("
            SELECT count(*)
            FROM aluno a
            LEFT JOIN selecionado s ON s.id_aluno = a.id
            WHERE s.id_aluno IS NULL
        ");
        $result = $stmt_nao_selecionados->fetch(PDO::FETCH_ASSOC);

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

    function fichas_concedentes()
    {
        $sql = "SELECT id, nome_fantasia, razao_social
                FROM ficha_concedente
                ORDER BY nome_fantasia ASC";

        $stmt = $this->connect->prepare($sql);
        $stmt->execute();

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

    private function normalizar_perfil($perfil): string
    {
        $perfil = trim((string) $perfil);
        if ($perfil === '') {
            return '';
        }

        $lower = function_exists('mb_strtolower') ? mb_strtolower($perfil, 'UTF-8') : strtolower($perfil);

        if (strpos($lower, 'desenvolv') !== false) {
            return 'desenvolvimento';
        }

        if (strpos($lower, 'design') !== false || strpos($lower, 'midia') !== false || strpos($lower, 'mídia') !== false) {
            return 'design';
        }

        if (strpos($lower, 'suporte') !== false || strpos($lower, 'rede') !== false) {
            return 'suporte';
        }

        if (strpos($lower, 'tutoria') !== false) {
            return 'tutoria';
        }

        return $lower;
    }

    function alunos($nome_perfil = 0, $search = '', $filtro = '')
    {
        $targetArea = $this->normalizar_perfil((string) $nome_perfil);

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
                WHERE NOT EXISTS (
                    SELECT 1 FROM selecao se
                    WHERE se.id_aluno = a.id AND (se.Status = '0' OR se.Status = '1')
                )";

        $stmt = $this->connect->prepare($sql);
        $stmt->execute();
        $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($targetArea)) {
            return $alunos;
        }

        $filtrados = [];
        foreach ($alunos as $aluno) {
            $area1 = $this->normalizar_perfil((string) ($aluno['perfil_opc1'] ?? ''));
            $area2 = $this->normalizar_perfil((string) ($aluno['perfil_opc2'] ?? ''));

            if ($area1 === $targetArea || $area2 === $targetArea) {
                $aluno['priority_group'] = ($area1 === $targetArea) ? 1 : 2;
                $filtrados[] = $aluno;
            }
        }

        usort($filtrados, function ($a, $b) {
            $priorityA = (int) ($a['priority_group'] ?? 99);
            $priorityB = (int) ($b['priority_group'] ?? 99);
            if ($priorityA !== $priorityB) {
                return $priorityA <=> $priorityB;
            }

            $scoreA = (float) ($a['score'] ?? 0);
            $scoreB = (float) ($b['score'] ?? 0);
            if ($scoreA !== $scoreB) {
                return $scoreB <=> $scoreA;
            }

            $mediaA = (float) ($a['medias'] ?? 0);
            $mediaB = (float) ($b['medias'] ?? 0);
            if ($mediaA !== $mediaB) {
                return $mediaB <=> $mediaA;
            }

            return strcmp((string) ($a['nome'] ?? ''), (string) ($b['nome'] ?? ''));
        });

        return $filtrados;
    }

    /**
     * Ranking do dashboard por id do perfil (1–4): inclui alunos em seleção (pendente),
     * trata variantes de nome para Design, e coloca aprovados nesta área ao final da lista.
     */
    function alunos_ranking_dashboard_por_perfil_id(int $id_perfil)
    {
        $id_perfil = (int) $id_perfil;
        $stmtNome = $this->connect->prepare('SELECT nome_perfil FROM perfis WHERE id = ? LIMIT 1');
        $stmtNome->execute([$id_perfil]);
        $rowP = $stmtNome->fetch(PDO::FETCH_ASSOC);
        if (!$rowP || empty($rowP['nome_perfil'])) {
            return [];
        }

        $aliases = [trim((string) $rowP['nome_perfil'])];
        if ($id_perfil === 2) {
            foreach ([
                'Design/Mídia',
                'Design/Social mídia',
                'Design/Mídias',
                'Design',
                'Design/Social mídia',
            ] as $alt) {
                $t = trim((string) $alt);
                if ($t !== '' && !in_array($t, $aliases, true)) {
                    $aliases[] = $t;
                }
            }
        }

        if ($id_perfil === 3) {
            foreach ([
                'Suporte/Redes',
                'Suporte / Redes',
                'Suporte',
                'Redes',
            ] as $alt) {
                $t = trim((string) $alt);
                if ($t !== '' && !in_array($t, $aliases, true)) {
                    $aliases[] = $t;
                }
            }
        }
        if ($id_perfil === 4) {
            foreach ([
                'Tutoria',
                'tutoria',
            ] as $alt) {
                $t = trim((string) $alt);
                if ($t !== '' && !in_array($t, $aliases, true)) {
                    $aliases[] = $t;
                }
            }
        }

        $n = count($aliases);
        $marks = implode(',', array_fill(0, $n, '?'));
        $inOpc1 = "a.perfil_opc1 IN ($marks)";
        $inOpc2 = "a.perfil_opc2 IN ($marks)";
        $whereMatch = "($inOpc1 OR $inOpc2)";

        $scoreSql = "(a.medias + (CASE WHEN a.projetos != '' AND a.projetos IS NOT NULL THEN 5 ELSE 0 END) - (COALESCE(a.ocorrencia,0) * 0.5) + (COALESCE(a.entregas_individuais,0) * 5) + (COALESCE(a.entregas_grupo,0) * 5))";

        $approvedExists = "EXISTS (SELECT 1 FROM selecionado s INNER JOIN vagas v ON v.id = s.id_vaga WHERE s.id_aluno = a.id AND v.id_perfil = $id_perfil)";
        $waitingExists = "EXISTS (SELECT 1 FROM selecao se INNER JOIN vagas v ON v.id = se.id_vaga AND v.id_perfil = $id_perfil LEFT JOIN selecionado s ON s.id_aluno = se.id_aluno AND s.id_vaga = se.id_vaga WHERE se.id_aluno = a.id AND s.id_aluno IS NULL)";

        $empresaAprov = "(SELECT c.nome FROM selecionado s INNER JOIN vagas v ON v.id = s.id_vaga AND v.id_perfil = ? INNER JOIN concedentes c ON c.id = v.id_concedente WHERE s.id_aluno = a.id LIMIT 1)";
        $empresaAguar = "(SELECT c.nome FROM selecao se INNER JOIN vagas v ON v.id = se.id_vaga AND v.id_perfil = ? LEFT JOIN selecionado s ON s.id_aluno = se.id_aluno AND s.id_vaga = se.id_vaga INNER JOIN concedentes c ON c.id = v.id_concedente WHERE se.id_aluno = a.id AND s.id_aluno IS NULL LIMIT 1)";

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
            $scoreSql AS score,
            CASE
                WHEN $inOpc1 THEN 1
                WHEN $inOpc2 THEN 2
                ELSE 3
            END AS priority_group,
            CASE
                WHEN $approvedExists THEN 'approved'
                WHEN $waitingExists THEN 'waiting'
                ELSE 'no_interview'
            END AS ranking_ui_status,
            COALESCE($empresaAprov, $empresaAguar) AS empresa,
            (SELECT nome_perfil FROM perfis WHERE id = ? LIMIT 1) AS perfil_empresa
        FROM aluno a
        WHERE $whereMatch
        ORDER BY
            CASE WHEN $approvedExists THEN 1 ELSE 0 END ASC,
            CASE
                WHEN $inOpc1 THEN 1
                WHEN $inOpc2 THEN 2
                ELSE 3
            END ASC,
            score DESC,
            a.medias DESC,
            COALESCE(a.ocorrencia, 0) ASC";

        $params = [];
        $params = array_merge($params, $aliases, $aliases);
        $params[] = $id_perfil;
        $params[] = $id_perfil;
        $params[] = $id_perfil;
        $params = array_merge($params, $aliases, $aliases);
        $params = array_merge($params, $aliases, $aliases);

        $stmt = $this->connect->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Perfis usados no dashboard (mesma ordem das vagas: id 1–4).
     */
    function perfis_dashboard_ranking()
    {
        $stmt = $this->connect->query("SELECT id, nome_perfil FROM perfis WHERE id IN (1,2,3,4) ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                    a.localidade,
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
    /**
     * Lista alunos por status visual:
     * - approved: ja esta em `selecionado`.
     * - waiting: esta em `selecao`, mas ainda nao foi aprovado.
     * - no_interview: ainda nao esta associado a processo seletivo.
     */
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
            COALESCE(v.id,0) as id,
            c.nome AS nome_empresa,
            COALESCE(p.nome_perfil,'Entrar em contato...') AS nome_perfil,
            COALESCE(v.quant_vaga,0) AS quant_vaga,
            COALESCE(v.quant_cand,0) AS quant_cand,
            v.data as data,
            v.hora as hora,
            v.tipo_vaga as tipo_vaga,
            c.id as id_empresa,
            v.id_perfil as id_perfil
        FROM concedentes c
        INNER JOIN vagas v 
            ON v.id_concedente = c.id
        LEFT JOIN perfis p 
            ON v.id_perfil = p.id
        WHERE 1=1";

    $params = [];

    if (!empty($search)) {
        $sql .= " AND (c.nome LIKE ? OR p.nome_perfil LIKE ?)";
        $searchTerm = "%{$search}%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }

    if (!empty($area)) {

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
            $sql .= " AND (v.id_perfil = ? OR v.id_perfil IS NULL)";
            $params[] = $perfil_id;
        }
    }

    if (!empty($empresa)) {
        $sql .= " AND c.id = ?";
        $params[] = $empresa;
    }

    $sql .= " ORDER BY c.nome ASC, v.data DESC, v.hora DESC";

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

    public function alunos_selecao_por_vaga(int $id_vaga): array
    {
        $stmt = $this->connect->prepare(
            "SELECT a.id, a.nome, a.contato, se.Status
             FROM aluno a
             INNER JOIN selecao se ON a.id = se.id_aluno
             WHERE se.id_vaga = :id_vaga
             ORDER BY a.nome ASC"
        );
        $stmt->execute([':id_vaga' => $id_vaga]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function alunos_finalizados_por_vaga(int $id_vaga): array
    {
        $stmt = $this->connect->prepare(
            "SELECT a.id, a.nome, a.contato
             FROM aluno a
             INNER JOIN selecionado s ON a.id = s.id_aluno
             WHERE s.id_vaga = :id_vaga
             ORDER BY a.nome ASC"
        );
        $stmt->execute([':id_vaga' => $id_vaga]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function status_vaga_por_id(int $id_vaga): string
    {
        $selecao = $this->connect->prepare("SELECT COUNT(*) as total FROM selecao WHERE id_vaga = :id_vaga");
        $selecao->execute([':id_vaga' => $id_vaga]);
        $emSelecao = (int) $selecao->fetch(PDO::FETCH_ASSOC)['total'];

        $finalizados = $this->connect->prepare("SELECT COUNT(*) as total FROM selecionado WHERE id_vaga = :id_vaga");
        $finalizados->execute([':id_vaga' => $id_vaga]);
        $aprovados = (int) $finalizados->fetch(PDO::FETCH_ASSOC)['total'];

        if ($aprovados > 0) {
            return 'finalizada';
        }

        if ($emSelecao > 0) {
            return 'em-selecao';
        }

        return 'sem-selecao';
    }

    function nome_empresa_por_vaga($id_vaga)
    {
        $stmt = $this->connect->prepare("SELECT c.nome FROM vagas v INNER JOIN concedentes c ON v.id_concedente = c.id WHERE v.id = :id_vaga LIMIT 1");
        $stmt->bindValue(':id_vaga', $id_vaga, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['nome'] : null;
    }

    /**
     * Aprova alunos selecionados para vagas.
     *
     * Atualiza o status em `selecao`, marca os demais candidatos da vaga como
     * nao selecionados e insere o aprovado em `selecionado` se ainda nao existir.
     */
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
