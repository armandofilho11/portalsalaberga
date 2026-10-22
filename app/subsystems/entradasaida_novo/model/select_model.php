<?php
// Compatibilidade para os relatórios antigos que ainda usam select_model.php
require_once __DIR__ . '/../app/main/models/Aluno.php';

class select_model {
    private $aluno;

    public function __construct()
    {
        $this->aluno = new Aluno();
    }

    // Mapeamentos simples para saídas de estágio por turma
    public function saida_estagio_3A()
    {
        return $this->aluno->listarSaidasEstagioPorTurma('3 ano', 'A');
    }

    public function saida_estagio_3B()
    {
        return $this->aluno->listarSaidasEstagioPorTurma('3 ano', 'B');
    }

    public function saida_estagio_3C()
    {
        return $this->aluno->listarSaidasEstagioPorTurma('3 ano', 'C');
    }

    public function saida_estagio_3D()
    {
        return $this->aluno->listarSaidasEstagioPorTurma('3 ano', 'D');
    }

    // Versões para relatórios (mesma coisa)
    public function saida_estagio_3A_relatorio()
    {
        return $this->saida_estagio_3A();
    }

    public function saida_estagio_3B_relatorio()
    {
        return $this->saida_estagio_3B();
    }

    public function saida_estagio_3C_relatorio()
    {
        return $this->saida_estagio_3C();
    }

    public function saida_estagio_3D_relatorio()
    {
        return $this->saida_estagio_3D();
    }

    // Versões por data: filtram o resultado por turma
    private function filterByTurma(array $rows, string $turma)
    {
        return array_values(array_filter($rows, function ($r) use ($turma) {
            return isset($r['turma']) && trim(strtoupper($r['turma'])) === strtoupper($turma);
        }));
    }

    public function saida_estagio_3A_relatorio_dia($data)
    {
        $rows = $this->aluno->listarSaidasEstagioPorData($data);
        return $this->filterByTurma($rows, 'A');
    }

    public function saida_estagio_3B_relatorio_dia($data)
    {
        $rows = $this->aluno->listarSaidasEstagioPorData($data);
        return $this->filterByTurma($rows, 'B');
    }

    public function saida_estagio_3C_relatorio_dia($data)
    {
        $rows = $this->aluno->listarSaidasEstagioPorData($data);
        return $this->filterByTurma($rows, 'C');
    }

    public function saida_estagio_3D_relatorio_dia($data)
    {
        $rows = $this->aluno->listarSaidasEstagioPorData($data);
        return $this->filterByTurma($rows, 'D');
    }

    // Métodos de "ausentes" — implementações simples (retornam array vazio)
    // para evitar erros; podem ser aprimorados depois com lógica de comparação
    public function alunos_ausentes_3A_relatorio()
    {
        return [];
    }

    public function alunos_ausentes_3B_relatorio()
    {
        return [];
    }

    public function alunos_ausentes_3C_relatorio()
    {
        return [];
    }

    public function alunos_ausentes_3D_relatorio()
    {
        return [];
    }

    public function alunos_ausentes_3A_relatorio_dia($data)
    {
        return [];
    }

    public function alunos_ausentes_3B_relatorio_dia($data)
    {
        return [];
    }

    public function alunos_ausentes_3C_relatorio_dia($data)
    {
        return [];
    }

    public function alunos_ausentes_3D_relatorio_dia($data)
    {
        return [];
    }

    // Fallback genérico para evitar erros quando métodos inesperados forem chamados
    public function __call($name, $arguments)
    {
        return [];
    }
}
