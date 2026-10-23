<?php
$caminho = "../config/connect.php";
if (file_exists($caminho)) {
    require_once('../config/connect.php');
} else {
    require_once('../../config/connect.php');
}

class select_model extends connect
{
    private $catalogo;

    function __construct()
    {
        parent::__construct();
        $this->catalogo = "catalogo";
    }

    public function select_subgenero($genero)
    {

        $sql_idgenero = $this->connect->query("SELECT id FROM genero WHERE generos = '$genero'");
        $id_generos = $sql_idgenero->fetch(PDO::FETCH_ASSOC);
        $id_genero = $id_generos['id'];
        $select_subgenero = $this->connect->query("SELECT * FROM subgenero WHERE id_genero = '$id_genero'");
        $nome_subgenero = $select_subgenero->fetchAll(PDO::FETCH_ASSOC);

        return $nome_subgenero;
    }
    public function select_genero()
    {
        $sql_genero = $this->connect->query("SELECT * FROM genero");
        $generos = $sql_genero->fetchAll(PDO::FETCH_ASSOC);

        return $generos;
    }
    public function select_qrcode($prateleira, $estante)
    {

        $sql_acervo = $this->connect->query("SELECT * FROM catalogo WHERE pratileira = '$prateleira' AND estante = '$estante'");
        $acervo = $sql_acervo->fetchAll(PDO::FETCH_ASSOC);

        return $acervo;
    }
    public function select_nome_livro()
    {
        $sql_nome_livro = $this->connect->query(
            "SELECT 
                c.id,
                c.titulo_livro,
                c.edicao,
                c.editora,
                c.quantidade,
                g.generos,
                sg.subgenero,
                c.ano_publicacao,
                c.corredor,
                c.estantes,
                c.prateleiras,
                c.ficcao,
                c.brasileira,
                c.cativo,
                GROUP_CONCAT(CONCAT(a.nome_autor, ' ', a.sobrenome_autor) SEPARATOR ', ') AS autores
            FROM catalogo c 
            LEFT JOIN genero g ON c.id_genero = g.id 
            LEFT JOIN subgenero sg ON c.id_subgenero = sg.id 
            LEFT JOIN livros_autores l ON c.id = l.id_livro 
            LEFT JOIN autores a ON l.id_autor = a.id 
            GROUP BY c.id, c.titulo_livro, c.edicao, c.editora, c.quantidade, g.generos, sg.subgenero, 
                     c.ano_publicacao, c.corredor, c.estantes, c.prateleiras, c.ficcao, c.brasileira, c.cativo
            ORDER BY c.titulo_livro, c.edicao"
        );
        $nome = $sql_nome_livro->fetchAll(PDO::FETCH_ASSOC);

        return $nome;
    }
    public function select_nome_livro_especifico()
    {
        $sql_nome_livro = $this->connect->query("SELECT DISTINCT titulo_livro FROM catalogo");
        $nome = $sql_nome_livro->fetchAll(PDO::FETCH_ASSOC);

        return $nome;
    }

    public function select_livro_especifico($titulos)
    {
        $array_dados = []; // Inicializar o array antes do loop

        foreach ($titulos as $titulo) {
            // Usar prepared statement para evitar SQL Injection
            $select_dados_livro = $this->connect->prepare("SELECT * FROM catalogo WHERE titulo_livro = ?");
            $select_dados_livro->execute([$titulo]);

            $dados_livros = $select_dados_livro->fetchAll(PDO::FETCH_ASSOC);

            array_push($array_dados, $dados_livros);
        }

        return $array_dados;
    }
    public function select_nome_autor()
    {
        $sql_nome_autor = $this->connect->query("SELECT * FROM autores");
        $nome_autor = $sql_nome_autor->fetchAll(PDO::FETCH_ASSOC);

        return $nome_autor;
    }
    public function select_nome_autor_livro()
    {
        $sql_nome_autor = $this->connect->query("SELECT * FROM autores");
        $nome_autor = $sql_nome_autor->fetchAll(PDO::FETCH_ASSOC);

        return $nomes_autores = $nome_autor['nome_autor'].' '.$nome_autor['sobrenome_autor'];
    }
    public function id_aluno_selecionado($id_aluno_selecionado) {
        if ($id_aluno_selecionado) {
            $stmt = $this->connect->prepare("
                SELECT 
                    aluno.nome,
                    turma.id_turma, turma.ano, turma.turma,
                    curso.id_curso, curso.curso 
                FROM aluno
                JOIN turma ON aluno.id_turma = turma.id_turma
                JOIN curso ON aluno.id_curso = curso.id_curso
                WHERE aluno.id_aluno = ?
            ");
            $stmt->execute([$id_aluno_selecionado]);
            $info = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($info) {
                $nome = $info['nome'];
                $turma = $info['ano'] . ' ' . $info['turma'];
                $curso = $info['curso'];
                return ['nome' => $nome, 'turma' => $turma, 'curso' => $curso, 'id_aluno' => $id_aluno_selecionado];
            }
        }
        return null;
    }
    public function select_livros()
    {

        $sql_livro = $this->connect->query('SELECT id, titulo_livro FROM catalogo');
        $livros = $sql_livro->fetchAll(PDO::FETCH_ASSOC);

        return $livros;
    }
    public function id_livro_selecionado($id_livro_selecionado)
    {
        if ($id_livro_selecionado) {
            $stmt = $this->connect->prepare("SELECT * FROM catalogo WHERE id = ?");
            $stmt->execute([$id_livro_selecionado]);
            $info = $stmt->fetch(PDO::FETCH_ASSOC);
            return $info;
        }
        return null;
    }
    public function select_emprestimo()
    {
        $sql_emprestimo = $this->connect->query(
            'SELECT emprestimo.id, emprestimo.id_aluno, emprestimo.id_catalogo, catalogo.titulo_livro 
                    FROM emprestimo 
                    JOIN catalogo ON emprestimo.id_catalogo = catalogo.id WHERE status = 1'
        );
        $emprestimos = $sql_emprestimo->fetchAll(PDO::FETCH_ASSOC);

        return $emprestimos;
    }
    public function id_emprestimo_selecionado($id_emprestimo_selecionado)
    {
        if ($id_emprestimo_selecionado) {
            $stmt = $this->connect->prepare("SELECT * FROM emprestimo WHERE id = ?");
            $stmt->execute([$id_emprestimo_selecionado]);
            $info = $stmt->fetch(PDO::FETCH_ASSOC);
            return $info;
        }
        return null;
    }
    public function dados_aluno($id_emprestimo)
    {
        if ($id_emprestimo) {
            $stmt = $this->connect->prepare('
                SELECT 
                    aluno.nome,
                    turma.ano, turma.turma,
                    curso.curso
                FROM emprestimo
                JOIN aluno ON emprestimo.id_aluno = aluno.id_aluno
                JOIN turma ON aluno.id_turma = turma.id_turma
                JOIN curso ON aluno.id_curso = curso.id_curso
                WHERE emprestimo.id = ?
            ');
            $stmt->execute([$id_emprestimo]);
            $info = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($info) {
                $turma = $info['ano'] . ' ' . $info['turma'];
                return [
                    'nome' => $info['nome'],
                    'turma' => $turma,
                    'curso' => $info['curso']
                ];
            }
        }
        return null;
    }
    public function get_turma_nome($id_turma)
    {
        if ($id_turma === null) {
            return false;
        }
        try {
            $sql = "SELECT CONCAT(ano, ' ', turma) as nome_turma FROM turma WHERE id_turma = :id_turma";
            $stmt = $this->connect->prepare($sql);
            $stmt->bindParam(':id_turma', $id_turma, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Debug para verificar os dados
            error_log("Nome da turma encontrado: " . print_r($result, true));

            return $result ? $result['nome_turma'] : false;
        } catch (PDOException $e) {
            error_log("Erro ao buscar nome da turma no model: " . $e->getMessage());
            return false;
        }
    }


    /**
     * Busca o aluno que mais pegou livros emprestados em uma turma e mês específicos.
     * Filtra por mês e pelo ano atual.
     * @param int $id_turma O ID da turma.
     * @param int $mes_numero O número do mês (1 a 12).
     * @return string|false Retorna o nome do aluno destaque ou false se não encontrado/erro.
     */

    public function get_aluno_destaque($id_turma, $mes_numero)
    {
        if ($id_turma === null || $mes_numero === null) {
            return false;
        }
        try {
            // Consulta para encontrar o aluno com mais empréstimos na turma e mês DO ANO ATUAL
            $sql = "
                 SELECT
                     a.nome
                 FROM
                     aluno a
                 JOIN
                     emprestimo e ON a.id_aluno = e.id_aluno
                 WHERE
                     a.id_turma = :id_turma
                     AND MONTH(e.data_emprestimo) = :mes_numero
                     AND YEAR(e.data_emprestimo) = YEAR(CURDATE()) -- Filtra pelo ano atual
                 GROUP BY
                     a.id_aluno, a.nome
                 ORDER BY
                     COUNT(e.id) DESC
                 LIMIT 1
             ";


            $stmt = $this->connect->prepare($sql);
            $stmt->bindParam(':id_turma', $id_turma, PDO::PARAM_INT);
            $stmt->bindParam(':mes_numero', $mes_numero, PDO::PARAM_INT);
            // Não precisa bindar o ano, pois YEAR(CURDATE()) é resolvido no lado do BD


            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);


            // Retorna o nome do aluno ou uma string vazia se não houver resultado
            return $result ? $result['nome'] : '';
        } catch (PDOException $e) {
            error_log("Erro ao buscar aluno destaque no model: " . $e->getMessage());
            return false; // Retorna false em caso de erro de banco de dados
        }
    }

    public function select_turmas()
    {
        try {
            $sql = "SELECT id_turma, CONCAT(ano, ' ', turma) as nome_turma FROM turma ORDER BY ano, turma";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar turmas no model: " . $e->getMessage());
            return false;
        }
    }
    public function select_aluno()
    {

        $sql_aluno = $this->connect->query('SELECT id_aluno, nome FROM aluno');
        $alunos = $sql_aluno->fetchAll(PDO::FETCH_ASSOC);

        return $alunos;
    }
}
