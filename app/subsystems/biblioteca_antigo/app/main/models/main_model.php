<?php

require_once('../config/connect.php');

class main_model extends connect
{
    //atrivbutos
    private $catalogo;

    function __construct()
    {
        parent::__construct();
        $this->catalogo = "catalogo";
    }

    public function cadastrar_livros($nome, $sobrenome, $titulo, $data, $editora, $edicao, $quantidade, $corredor, $estante, $prateleira, $subgenero, $literatura, $ficcao, $cativo)
    {
        $sql_check = $this->connect->prepare("SELECT * FROM catalogo WHERE titulo_livro = :titulo AND edicao = :edicao AND editora = :editora");
        
        $sql_check->bindValue(':titulo', $titulo);
        $sql_check->bindValue(':edicao', $edicao);
        $sql_check->bindValue(':editora', $editora);
        $sql_check->execute();

        $check = $sql_check->fetch(PDO::FETCH_ASSOC);

        if (empty($check)) {

            $sql_id = $this->connect->query("SELECT id, id_genero FROM subgenero WHERE subgenero = '$subgenero'");

            $id = $sql_id->fetch(PDO::FETCH_ASSOC);

            $cadastro_livro = $this->connect->prepare("INSERT INTO $this->catalogo VALUES (null, :titulo_livro, :ano_publicacao, :editora, :edicao, :quantidade, :corredor, :estante, :prateleira, :genero, :subgenero, :ficcao, :brasileira, :cativo)");

            $cadastro_livro->bindValue(':titulo_livro', $titulo);
            $cadastro_livro->bindValue(':ano_publicacao', $data);
            $cadastro_livro->bindValue(':editora', $editora);
            $cadastro_livro->bindValue(':edicao', $edicao);
            $cadastro_livro->bindValue(':quantidade', $quantidade);
            $cadastro_livro->bindValue(':corredor', $corredor);
            $cadastro_livro->bindValue(':estante', $estante);
            $cadastro_livro->bindValue(':prateleira', $prateleira);
            $cadastro_livro->bindValue(':genero', $id['id_genero']);
            $cadastro_livro->bindValue(':subgenero', $id['id']);
            $cadastro_livro->bindValue(':ficcao', $ficcao);
            $cadastro_livro->bindValue(':brasileira', $literatura);
            $cadastro_livro->bindValue(':cativo', $cativo);

            $cadastro_livro->execute();

            $sql_id_livro = $this->connect->prepare("SELECT id FROM catalogo WHERE titulo_livro = :titulo AND editora = :editora AND edicao = :edicao");
            $sql_id_livro->bindValue(':titulo', $titulo);
            $sql_id_livro->bindValue(':editora', $editora);
            $sql_id_livro->bindValue(':edicao', $edicao);
            $sql_id_livro->execute();

            $id_livro = $sql_id_livro->fetch(PDO::FETCH_ASSOC);

            $tamanho_array = count($nome) - 1;
            
            /** Inicia um loop para processar cada autor do livro */
            for ($x = 0; $x <= $tamanho_array; $x++) {

                $nome_array = $nome[$x];
                $sobrenome_array = $sobrenome[$x];

                $sql_check = $this->connect->prepare("SELECT nome_autor, sobrenome_autor FROM autores WHERE nome_autor = :nome_autor AND sobrenome_autor = :sobrenome_autor");
                $sql_check->bindValue(':nome_autor', $nome_array);
                $sql_check->bindValue(':sobrenome_autor', $sobrenome_array);
                $sql_check->execute();
                $autores = $sql_check->fetch(PDO::FETCH_ASSOC);

                /** Se o autor não existe, cadastra um novo */
                if (empty($autores)) {

                    $sql_autor = $this->connect->prepare("INSERT INTO autores VALUES(NULL, :nome_autor, :sobrenome_autor)");
                    $sql_autor->bindValue(':nome_autor', $nome_array);
                    $sql_autor->bindValue(':sobrenome_autor', $sobrenome_array);
                    $sql_autor->execute();

                    $sql_check = $this->connect->prepare("SELECT id FROM autores WHERE nome_autor = :nome_autor AND sobrenome_autor = :sobrenome_autor");
                    $sql_check->bindValue(':nome_autor', $nome_array);
                    $sql_check->bindValue(':sobrenome_autor', $sobrenome_array);
                    $sql_check->execute();
                    $id_autor = $sql_check->fetch(PDO::FETCH_ASSOC);

                    $sql_id_autor_livro = $this->connect->prepare("INSERT INTO livros_autores VALUES(NULL, :id_autor, :id_livro)");
                    $sql_id_autor_livro->bindValue(':id_autor', $id_autor['id']);
                    $sql_id_autor_livro->bindValue(':id_livro', $id_livro['id']);
                    $sql_id_autor_livro->execute();
                } else {
                    /** Se o autor já existe, apenas busca o id dele */
                    $sql_check = $this->connect->prepare("SELECT id FROM autores WHERE nome_autor = :nome_autor AND sobrenome_autor = :sobrenome_autor");
                    $sql_check->bindValue(':nome_autor', $nome_array);
                    $sql_check->bindValue(':sobrenome_autor', $sobrenome_array);
                    $sql_check->execute();
                    $id_autor = $sql_check->fetch(PDO::FETCH_ASSOC);

                    $sql_id_autor_livro = $this->connect->prepare("INSERT INTO livros_autores VALUES(NULL, :id_autor, :id_livro)");
                    $sql_id_autor_livro->bindValue(':id_autor', $id_autor['id']);
                    $sql_id_autor_livro->bindValue(':id_livro', $id_livro['id']);
                    $sql_id_autor_livro->execute();
                }
            }

            /** Verifica se as inserções do livro e da relação autor-livro foram bem-sucedidas */
            if ($cadastro_livro && $sql_id_autor_livro) {
                /** Retorna 1 se o cadastro foi concluído com sucesso */
                return 1;
            } else {
                /** Retorna 2 se houve algum erro no cadastro */
                return 2;
            }
        } else {
            /** Retorna 3 se o livro já existe no catálogo */
            return 3;
        }
    }

    public function cadastrar_subgenero($genero, $subgenero)
    {
        $sql_idgenero = $this->connect->prepare("SELECT id FROM genero WHERE generos = :generos");
        $sql_idgenero->bindValue(':generos', $genero);
        $sql_idgenero->execute();
        $id_genero = $sql_idgenero->fetch(PDO::FETCH_ASSOC);

        $sql_check = $this->connect->prepare("SELECT * FROM subgenero WHERE subgenero = :subgenero AND id_genero = :id_genero");
        $sql_check->bindValue(':subgenero', $subgenero);
        $sql_check->bindValue(':id_genero', $id_genero['id']);
        $sql_check->execute();
        $subgeneros = $sql_check->fetch(PDO::FETCH_ASSOC);

        if (empty($subgeneros)) {

            $sql_subgenero = $this->connect->prepare("INSERT INTO subgenero VALUES (NULL, :subgenero, :id_genero)");
            $sql_subgenero->bindValue(':subgenero', $subgenero);
            $sql_subgenero->bindValue(':id_genero', $id_genero['id']);
            $sql_subgenero->execute();

            /** Verifica se a inserção foi bem-sucedida */
            if ($sql_subgenero) {
                /** Retorna 1 se o subgênero foi cadastrado com sucesso */
                return 1;
            } else {
                /** Retorna 2 se houve erro ao cadastrar o subgênero */
                return 2;
            }
        } else {
            /** Retorna 3 se o subgênero já existe */
            return 3;
        }
    }

    public function cadastrar_genero($nome_genero)
    {
        $sql_check = $this->connect->prepare("SELECT * FROM genero WHERE  generos = :genero");
        $sql_check->bindValue(':genero', $nome_genero);
        $sql_check->execute();
        $generos = $sql_check->fetch(PDO::FETCH_ASSOC);

        if (empty($generos)) {

            $sql_genero = $this->connect->prepare("INSERT INTO genero VALUES (NULL, :novo_genero)");
            $sql_genero->bindValue(':novo_genero', $nome_genero);
            $sql_genero->execute();

            $sql_id_genero = $this->connect->prepare("SELECT id FROM genero WHERE generos = :genero2");
            $sql_id_genero->bindValue(':genero2', $nome_genero);
            $sql_id_genero->execute();
            $id_genero = $sql_id_genero->fetch(PDO::FETCH_ASSOC);

            $sql_insert_subgenero = $this->connect->prepare("INSERT INTO subgenero VALUES (NULL, :genero2, :id_genero)");
            $sql_insert_subgenero->bindValue(':genero2', $nome_genero);
            $sql_insert_subgenero->bindValue(':id_genero', $id_genero['id']);
            $sql_insert_subgenero->execute();

            if ($sql_insert_subgenero) {
                /** Retorna 1 se o gênero e subgênero foram cadastrados com sucesso */
                return 1;
            } else {
                /** Retorna 2 se houve erro ao cadastrar */
                return 2;
            }
        } else {
            /** Retorna 3 se o gênero já existe */
            return 3;
        }
    }

    public function excluir_livro($id_livro)
    {
        foreach ($id_livro as $id) {
            $sql_excluir = $this->connect->prepare("DELETE FROM livros_autores WHERE id_livro = :id");
            $sql_excluir->bindValue(':id', $id);
            $sql_excluir->execute();

            $sql_excluir = $this->connect->prepare("DELETE FROM catalogo WHERE id = :id");
            $sql_excluir->bindValue(':id', $id);
            $sql_excluir->execute();
        }
        if ($sql_excluir) {
            return 1;
        } else {

            return 2;
        }
    }

    public function editar_livro($id_livro, $titulo, $ano_publicacao, $editora, $edicao, $quantidade, $corredor, $estante, $prateleira, $genero, $subgenero, $literatura, $ficcao, $cativo)
    {
        $sql_editar = $this->connect->prepare("UPDATE catalogo SET titulo_livro = :titulo, ano_publicacao = :ano_publicacao, editora = :editora, edicao = :edicao, quantidade = :quantidade, corredor = :corredor, estantes = :estante, prateleiras = :prateleira, id_genero = :genero, id_subgenero = :subgenero, brasileira = :literatura, ficcao = :ficcao, cativo = :cativo WHERE id = :id");
        $sql_editar->bindValue(':id', $id_livro);
        $sql_editar->bindValue(':titulo', $titulo);
        $sql_editar->bindValue(':ano_publicacao', $ano_publicacao);
        $sql_editar->bindValue(':editora', $editora);
        $sql_editar->bindValue(':edicao', $edicao);
        $sql_editar->bindValue(':quantidade', $quantidade);
        $sql_editar->bindValue(':corredor', $corredor);
        $sql_editar->bindValue(':estante', $estante);
        $sql_editar->bindValue(':prateleira', $prateleira);
        $sql_editar->bindValue(':genero', $genero);
        $sql_editar->bindValue(':subgenero', $subgenero);
        $sql_editar->bindValue(':literatura', $literatura);
        $sql_editar->bindValue(':ficcao', $ficcao);
        $sql_editar->bindValue(':cativo', $cativo);
        $sql_editar->execute();

        if ($sql_editar) {
            return 1;
        } else {
            return 2;
        }
    }
    public function editar_autor($id_autor, $nome, $sobrenome)
    {
        $sql_editar = $this->connect->prepare("UPDATE autores SET nome_autor = :nome, sobrenome_autor = :sobrenome WHERE id = :id");
        $sql_editar->bindValue(':id', $id_autor);
        $sql_editar->bindValue(':nome', $nome);
        $sql_editar->bindValue(':sobrenome', $sobrenome);
        $sql_editar->execute();

        if ($sql_editar) {
            return 1;
        } else {
            return 2;
        }
    }
    public function registrar_emprestimo($id_aluno, $id_catalogo, $data_emprestimo, $data_devolucao_estipulada)
    {
        try {
            // Verifica se o empréstimo já existe
            $sql_check = $this->connect->prepare("SELECT * FROM emprestimo WHERE id_aluno = :id_aluno AND id_catalogo = :id_catalogo AND data_emprestimo = :data_emprestimo AND data_devolucao_estipulada = :data_devolucao_estipulada");
            $sql_check->bindValue(':id_aluno', $id_aluno);
            $sql_check->bindValue(':id_catalogo', $id_catalogo);
            $sql_check->bindValue(':data_emprestimo', $data_emprestimo);
            $sql_check->bindValue(':data_devolucao_estipulada', $data_devolucao_estipulada);
            $sql_check->execute();
            $check = $sql_check->fetch(PDO::FETCH_ASSOC);

            if (empty($check)) {
                // Prepara a query de inserção
                $registrar_emprestimo = $this->connect->prepare("INSERT INTO emprestimo (id_aluno, id_catalogo, data_emprestimo, data_devolucao_estipulada) VALUES (:id_aluno, :id_catalogo, :data_emprestimo, :data_devolucao_estipulada)");
                $registrar_emprestimo->bindValue(':id_aluno', $id_aluno);
                $registrar_emprestimo->bindValue(':id_catalogo', $id_catalogo);
                $registrar_emprestimo->bindValue(':data_emprestimo', $data_emprestimo);
                $registrar_emprestimo->bindValue(':data_devolucao_estipulada', $data_devolucao_estipulada);

                // Execute e verifique o resultado
                if ($registrar_emprestimo->execute()) {
                    error_log("Empréstimo registrado com sucesso.");
                    return true;
                } else {
                    error_log("Falha ao registrar o empréstimo.");
                    return false;
                }
            } else {
                error_log("Empréstimo já existe.");
                return false;
            }
        } catch (PDOException $e) {
            error_log("Erro no banco de dados: " . $e->getMessage());
            return false;
        }
    }

    public function registrar_devolucao($id_emprestimo, $data_devolucao)
    {
        // Verifica se o empréstimo existe
        $sql_check_devolucao = $this->connect->prepare("SELECT * FROM emprestimo WHERE id = :id_emprestimo");
        $sql_check_devolucao->bindValue(':id_emprestimo', $id_emprestimo);

        $sql_check_devolucao->execute();
        $devolucao = $sql_check_devolucao->fetch(PDO::FETCH_ASSOC);

        if (empty($devolucao)) {
            error_log("Empréstimo não encontrado: $id_emprestimo");
            return false;
        } else {
            // Verifica se a devolução já existe
            $sql_check = $this->connect->prepare("SELECT * FROM devolucao WHERE id_emprestimo = :id_emprestimo");
            $sql_check->bindValue(':id_emprestimo', $id_emprestimo);
            $sql_check->execute();
            $check = $sql_check->fetch(PDO::FETCH_ASSOC);

            if (empty($check)) {
                $sql_update_emprestimo = $this->connect->prepare("UPDATE emprestimo SET status = 0 WHERE id = :id_emprestimo");
                $sql_update_emprestimo->bindValue(':id_emprestimo', $id_emprestimo);
                $sql_update_emprestimo->execute();

                $registrar_devolucao = $this->connect->prepare("INSERT INTO devolucao VALUES (null, :id_emprestimo, :data_devolucao)");
                $registrar_devolucao->bindValue(':id_emprestimo', $id_emprestimo);
                $registrar_devolucao->bindValue(':data_devolucao', $data_devolucao);

                // Execute e verifique o resultado
                if ($registrar_devolucao->execute()) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }
}
