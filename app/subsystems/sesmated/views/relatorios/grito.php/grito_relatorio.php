<?php
class PDF extends connect
{
    public function __construct()
    {
        parent::__construct();
        $this->main();
    }

    private function getDadosRelatorio()
    {
        $query = "
            SELECT t.turma_id, t.nome_turma, c.nome_curso, c.curso_id,
                   rifa.valor_arrecadado, rifa.quantidades_rifas, a.nome AS nome_avaliador
            FROM turmas t
            INNER JOIN cursos c ON c.curso_id = t.curso_id
            INNER JOIN tarefa_01_rifas rifa ON rifa.turma_id = t.turma_id
            INNER JOIN avaliadores a ON a.id = rifa.id_usuario
            ORDER BY c.curso_id, t.nome_turma
        ";
        $stmt = $this->connect->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function main()
    {
        $fpdf = new FPDF('P', 'pt', 'A4');
        $fpdf->AliasNbPages();

        $fpdf->Output('relatorio_acervo.pdf', 'I');
    }
}

$relatorio = new PDF();
