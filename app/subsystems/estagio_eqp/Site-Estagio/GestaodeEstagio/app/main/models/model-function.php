<?php 


Class Usuarios {
    public function Login_aluno($email, $senha) {
        $pdo = new PDO("mysql:host=localhost;dbname=u750204740_gestaoestagio","root","");
        $consulta = 'SELECT * FROM aluno WHERE email = :email AND senha = :senha';
        $query = $pdo->prepare($consulta);
        $query->bindValue(":email", $email);
        $query->bindValue(":senha", $senha);
        $query->execute();

        if ($query->rowCount() > 0) {
            return $query->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }


    // Professores

    public function Login_professor($email, $senha) {
        $pdo = new PDO("mysql:host=localhost;dbname=u750204740_gestaoestagio","root","");
        $consulta = 'SELECT * FROM usuario WHERE email = :email AND senha = :senha';
        $query = $pdo->prepare($consulta);
        $query->bindValue(":email", $email);
        $query->bindValue(":senha", $senha);
        $query->execute();

        if ($query->rowCount() > 0) {
            return $query->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }
   

}

Class Cadastro{

    public function Cadastrar_empresa($nome, $contato, $endereco, $perfis, $vagas){
        $pdo = new PDO("mysql:host=localhost;dbname=u750204740_gestaoestagio","root","");
        $consulta = 'INSERT INTO concedentes (nome, contato, endereco, perfis, numero_vagas) VALUES (:nome, :contato, :endereco, :perfis, :numero_vagas)';
        $query = $pdo->prepare($consulta);
        $query->bindValue(":nome", $nome);
        $query->bindValue(":contato", $contato);
        $query->bindValue(":endereco", $endereco);
        $query->bindValue(":perfis", $perfis);
        $query->bindValue(":numero_vagas", $vagas);
        $query->execute();

        if ($query->rowCount() > 0) {
            $dado = $query->fetch();
            $_SESSION['idEmp'] = $dado['id'];
            return true;
        } else {
            return false;
        }
    } 


     public function Cadastrar_alunos($nome, $matricula, $contato, $curso, $email, $endereco, $senha){
        $pdo = new PDO("mysql:host=localhost;dbname=u750204740_gestaoestagio","root","");
        $consulta = 'INSERT INTO aluno VALUES (null,:nome,:matricula,:contato,:curso,:email,:endereco,:senha)';
        $query = $pdo->prepare($consulta);
        $query->bindValue(":nome", $nome);
        $query->bindValue(":matricula", $matricula);
        $query->bindValue(":contato", $contato);
        $query->bindValue(":curso", $curso);
        $query->bindValue(":email", $email);
        $query->bindValue(":endereco", $endereco);
        $query->bindValue(":senha", $senha);
        $query->execute();

        if ($query->rowCount() > 0) {
            $dado = $query->fetch();
            $_SESSION['idAluno'] = $dado['id'];

             return true;
            // header("Location: ../views/paginainicial.php");
            }else {
             return false;
            }
     } 


    public function excluir_empresa($id){
        $pdo = new PDO("mysql:host=localhost;dbname=u750204740_gestaoestagio","root","");
        
        try {
            // Inicia a transação
            $pdo->beginTransaction();
            
            // Primeiro, exclui os registros relacionados na tabela selecao
            $consulta_selecao = 'DELETE FROM selecao WHERE id_concedente = :id';
            $query_selecao = $pdo->prepare($consulta_selecao);
            $query_selecao->bindValue(":id", $id);
            $query_selecao->execute();
            
            // Depois, exclui a empresa
            $consulta = 'DELETE FROM concedentes WHERE id = :id';
            $query = $pdo->prepare($consulta);
            $query->bindValue(":id", $id);
            $query->execute();
            
            // Confirma a transação
            $pdo->commit();
            return true;
            
        } catch (PDOException $e) {
            // Em caso de erro, desfaz a transação
            $pdo->rollBack();
            throw $e;
        }
    }

    public function excluir_aluno($id){
        $pdo = new PDO("mysql:host=localhost;dbname=u750204740_gestaoestagio","root","");
        $consulta = 'delete from aluno where id = :id;';
        $query = $pdo->prepare($consulta);
        $query->bindValue(":id", $id);
        $query->execute();
    }

    public function editar_empresaById($id){
        $pdo = new PDO("mysql:host=localhost;dbname=u750204740_gestaoestagio","root","");
        $consulta = 'select * from concedentes where id = :id;';
        $query = $pdo->prepare($consulta);
        $query->bindValue(":id", $id);
        $query->execute();
        return $query->fetch();
    }

    public function editar_alunoById($id){
        $pdo = new PDO("mysql:host=localhost;dbname=u750204740_gestaoestagio","root","");
        $consulta = 'SELECT * FROM aluno WHERE id = :id';
        $query = $pdo->prepare($consulta);
        $query->bindValue(":id", $id);
        $query->execute();
        $resultado = $query->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado : false;
    }

    public function editar_empresa($id, $nome, $contato, $endereco, $perfis, $vagas){
        $pdo = new PDO("mysql:host=localhost;dbname=u750204740_gestaoestagio","root","");
        $consulta = "UPDATE concedentes SET nome = :nome, contato = :contato, endereco = :endereco, perfis = :perfis, numero_vagas = :numero_vagas WHERE id = :id;";
        $query = $pdo->prepare($consulta);
        $query->bindValue(":id", $id);
        $query->bindValue(":nome", $nome);
        $query->bindValue(":contato", $contato);
        $query->bindValue(":endereco", $endereco);
        $query->bindValue(":perfis", $perfis);
        $query->bindValue(":numero_vagas", $vagas);
        $query->execute();
        return $query->rowCount();
    }

    public function editar_aluno_sem_senha($id, $nome, $matricula, $contato, $curso, $email, $endereco){
        $pdo = new PDO("mysql:host=localhost;dbname=u750204740_gestaoestagio","root","");
        $consulta = "UPDATE aluno SET nome = :nome, matricula = :matricula, contato = :contato, curso = :curso, email = :email, endereco = :endereco WHERE id = :id;";
        $query = $pdo->prepare($consulta);
        $query->bindValue(":id", $id);
        $query->bindValue(":nome", $nome);
        $query->bindValue(":matricula", $matricula);
        $query->bindValue(":contato", $contato);
        $query->bindValue(":curso", $curso);
        $query->bindValue(":email", $email);
        $query->bindValue(":endereco", $endereco);
        $query->execute();
        return $query->rowCount() > 0;
    }

    public function cadastrar_professor($email, $senha){
        $pdo = new PDO("mysql:host=localhost;dbname=u750204740_gestaoestagio","root","");
        $consulta = 'INSERT INTO usuario VALUES (null,:email,:senha)';
        $query = $pdo->prepare($consulta);
        $query->bindValue(":email", $email);
        $query->bindValue(":senha", $senha);
        $query->execute();
    }

    public function cadastrar_selecao($hora, $local, $id_concedente, $data_inscricao, $id_aluno, $id_vaga){
        $pdo = new PDO("mysql:host=localhost;dbname=u750204740_gestaoestagio","root","");
        $consulta = 'INSERT INTO selecao (hora, local, id_concedente, status) VALUES (:hora, :local, :id_concedente, "pendente")';
        $query = $pdo->prepare($consulta);
        $query->bindValue(":hora", $hora);
        $query->bindValue(":local", $local);
        $query->bindValue(":id_concedente", $id_concedente);
        return $query->execute();
    }

    public function excluir_formulario($id){
        $pdo = new PDO("mysql:host=localhost;dbname=u750204740_gestaoestagio","root","");
        $consulta = 'delete from selecao where id = :id;';
        $query = $pdo->prepare($consulta);
        $query->bindValue(":id", $id);
        $query->execute();
    }

    // public function inscrever_aluno($id_formulario, $id_aluno){
    //     $pdo = new PDO("mysql:host=localhost;dbname=estagio","root","");
    //     $consulta = 'INSERT INTO inscricao VALUES (null,:id_formulario,:id_aluno)';
    //     $query = $pdo->prepare($consulta);
    //     $query->bindValue(":id_formulario", $id_formulario);
    //     $query->bindValue(":id_aluno", $id_aluno);
    //     $query->execute();
    // }

}

?>