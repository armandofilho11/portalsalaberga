<?php
require_once(__DIR__ . '/../config/connect.php');

class Professor extends connect {
    public function __construct() {
        parent::__construct();
    }

    public function testar_conexao(){
        return $this->conexao->getAttribute(PDO::ATTR_CONNECTION_STATUS);
    }

    public function validar_email_e_senha_prof($email, $senha){
        $sql = 'select * from professor WHERE email = :email AND senha = :senha';
        $prep = $this->conexao->prepare($sql);
        $prep->bindParam(':email', $email);
        $prep->bindParam(':senha', $senha);
        $prep->execute();

        return $prep->fetch(PDO::FETCH_ASSOC);
    }

    public function criar_questao($disciplina, $grau_de_dificuldade, $enunciado, $alternativaA, $alternativaB, $alternativaC, $alternativaD, $id_professor, $resposta_correta, $subtopico = null) {
        // Inserir questão
        $sql = "INSERT INTO questao (id, disciplina, enunciado, grau_de_dificuldade, id_professor, subtopico) VALUES (null, :disciplina, :enunciado, :grau_de_dificuldade, :id_professor, :subtopico)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':disciplina', $disciplina);
        $stmt->bindParam(':enunciado', $enunciado);
        $stmt->bindParam(':grau_de_dificuldade', $grau_de_dificuldade);
        $stmt->bindParam(':id_professor', $id_professor);
        $stmt->bindParam(':subtopico', $subtopico);
        $stmt->execute();

        $last_id = $this->conexao->lastInsertId();

        // Alternativas
        $alternativas = [
            'A' => $alternativaA,
            'B' => $alternativaB,
            'C' => $alternativaC,
            'D' => $alternativaD
        ];

        foreach ($alternativas as $letra => $texto) {
            $resposta = ($resposta_correta === $letra) ? 'sim' : 'nao';
            $sql_alt = "INSERT INTO alternativas (id, id_questao, texto, resposta) VALUES (null, :id_questao, :texto, :resposta)";
            $stmt = $this->conexao->prepare($sql_alt);
            $stmt->bindParam(':id_questao', $last_id);
            $stmt->bindParam(':texto', $texto);
            $stmt->bindParam(':resposta', $resposta);
            $stmt->execute();
        }

        return true;
    }
  
    public function acessar_banco($enunciado, $disciplina, $grau_de_dificuldade, $id_professor, $subtopico = null) {
        try {
            // Função auxiliar para buscar alternativas
            $buscar_alternativas = function($id_questao) {
                $sql_alt = "SELECT * FROM alternativas WHERE id_questao = :id_questao";
                $stmt_alt = $this->conexao->prepare($sql_alt);
                $stmt_alt->bindValue(":id_questao", $id_questao);
                $stmt_alt->execute();
                return $stmt_alt->fetchAll(PDO::FETCH_ASSOC);
            };

            // Construir a consulta base
            $sql = "SELECT * FROM questao WHERE 1=1";
            $params = array();

            // Adicionar condições apenas se os parâmetros não estiverem vazios
            if (!empty($disciplina)) {
                $sql .= " AND disciplina = :disciplina";
                $params[':disciplina'] = $disciplina;
            }

            if (!empty($enunciado)) {
                $sql .= " AND enunciado LIKE :enunciado";
                $params[':enunciado'] = '%' . $enunciado . '%';
            }

            if (!empty($grau_de_dificuldade)) {
                $sql .= " AND grau_de_dificuldade = :grau_de_dificuldade";
                $params[':grau_de_dificuldade'] = $grau_de_dificuldade;
            }

            if (!empty($id_professor)) {
                $sql .= " AND id_professor = :id_professor";
                $params[':id_professor'] = $id_professor;
            }

            if (!empty($subtopico)) {
                $sql .= " AND subtopico = :subtopico";
                $params[':subtopico'] = $subtopico;
            }

            // Debug information
            error_log("SQL Query: " . $sql);
            error_log("Parameters: " . print_r($params, true));

            $stmt = $this->conexao->prepare($sql);
            
            // Vincular os parâmetros
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Debug information
            error_log("Raw results: " . print_r($resultado, true));

            // Adicionar alternativas para cada questão
            foreach ($resultado as &$questao) {
                $questao['alternativas'] = $buscar_alternativas($questao['id']);
            }

            return $resultado;

        } catch(PDOException $e) {
            // Debug information
            error_log("Database error: " . $e->getMessage());
            // Em caso de erro, retornar array vazio
            return array();
        }
    }

    public function acessar_banco_geral(){
        $sql = "select * from questao";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchall(PDO::FETCH_ASSOC);
        return $resultado;
    }
    
    
    
    public function criar_prova($id_questao, $nome_prova, $tipo, $dificuldade,$turma) {
        try {
            // 1. Inserir a avaliação na tabela 'avaliacao'
            $sql = "insert INTO avaliacao (tipo, nome, dificuldade,ano) VALUES (:tipo, :nome_prova, :dificuldade, :ano)";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":tipo", $tipo);
            $stmt->bindValue(":nome_prova", $nome_prova);
            $stmt->bindValue(":dificuldade", $dificuldade);
            $stmt->bindValue(":ano", $turma);
            $stmt->execute();

            // 2. Obter o ID da avaliação recém-inserida
            $id_avaliacao = $this->conexao->lastInsertId();

            // 3. Inserir os IDs das questões na tabela 'questao_prova'
            $sql2 = "INSERT INTO questao_prova (id_avaliacao, id_questao) VALUES (:id_avaliacao, :id_questao)";
            $stmt2 = $this->conexao->prepare($sql2);

            // 4. Preparar a atualização do status da questão
            $sql3 = "UPDATE questao SET status = 1 WHERE id = :id_questao";
            $stmt3 = $this->conexao->prepare($sql3);

            // Verifique se $id_questao é um array e percorra cada ID
            if (is_array($id_questao)) {
                foreach ($id_questao as $id_q) {
                    // Inserir na questao_prova
                    $stmt2->bindValue(":id_avaliacao", $id_avaliacao);
                    $stmt2->bindValue(":id_questao", $id_q);
                    $stmt2->execute();

                    // Atualizar status da questão
                    $stmt3->bindValue(":id_questao", $id_q);
                    $stmt3->execute();
                }
            }

            return true;

        } catch (PDOException $e) {
            echo '<strong>Erro ao criar prova: </strong>' . $e->getMessage();
            return false;
        }
    }


    public function acessar_questoes_prova($dificuldade, $disciplina) {
        try {
            if (empty($dificuldade) && empty($disciplina)) {
                echo "Os dois campos (dificuldade e disciplina) estão vazios";
                return [];
            } elseif (empty($disciplina)) {
                $sql = "select * FROM questao WHERE grau_de_dificuldade = :dificuldade";
                $stmt = $this->conexao->prepare($sql);
                $stmt->bindValue(':dificuldade', $dificuldade);
            } elseif (empty($dificuldade)) {
                $sql = "select * FROM questao WHERE disciplina = :disciplina";
                $stmt = $this->conexao->prepare($sql);
                $stmt->bindValue(':disciplina', $disciplina);
            } else {
                $sql = "select * FROM questao WHERE disciplina = :disciplina AND grau_de_dificuldade = :dificuldade";
                $stmt = $this->conexao->prepare($sql);
                $stmt->bindValue(':disciplina', $disciplina);
                $stmt->bindValue(':dificuldade', $dificuldade);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e) {
            echo '<strong>Erro ao acessar questões: </strong>' . $e->getMessage();
            return [];
        }
    }

    public function gerar_relatorio_coletivo($querpdf,$turma,$disciplina){
        if(empty($querpdf)){
            $sql = "select * from relatorio_professor where disciplina = :disciplina and turma = :turma";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindvalue(':disciplina',$disciplina);
            $stmt->bindvalue(':turma',$turma);
            $stmt->execute();
            $resultado = $stmt->fetchall(PDO::FETCH_ASSOC);
            return $resultado;
        } else {
            $sql = "select * from relatorio_professor where disciplina = :disciplina and turma = :turma";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindvalue(':disciplina',$disciplina);
            $stmt->bindvalue(':turma',$turma);
            $stmt->execute();
            $resultado = $stmt->fetchall(PDO::FETCH_ASSOC);
        
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial','B',16);
            $pdf->Cell(40,10,$resultado);
            $pdf->Output();
        }
    }

    public function gerar_relatorio_individual($turma,$disciplina){
        $sql = "select * from relatorio_professor_individual where turma = :turma and disciplina = :disciplina";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindvalue(":turma",$turma."b");
        $stmt->bindvalue(":disciplina",$disciplina);
        $stmt->execute();
        $resultado = $stmt->fetchall(PDO::FETCH_ASSOC);
        return $resultado;
    }

    public function visualizar_alunos($nomealuno = '', $anoaluno = '', $id = null) {
        try {
            $sql = "SELECT * FROM aluno WHERE 1=1";
            $params = array();

            if (!empty($nomealuno)) {
                $sql .= " AND nome LIKE :nome";
                $params[':nome'] = "%".$nomealuno."%";
            }
            
            if (!empty($anoaluno)) {
                $sql .= " AND ano = :anoaluno";
                $params[':anoaluno'] = $anoaluno;
            }

            if (!empty($id)) {
                $sql .= " AND id = :id";
                $params[':id'] = $id;
            }

            $sql .= " ORDER BY nome ASC";
            
            // Debug
            error_log("SQL Query: " . $sql);
            error_log("Params: " . print_r($params, true));
            
            $stmt = $this->conexao->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Debug
            error_log("Resultados encontrados: " . count($resultado));
            
            return $resultado;
        } catch (PDOException $e) {
            error_log("Erro na consulta: " . $e->getMessage());
            return array();
        }
    }

    public function visualizar_avaliacoes(){
        $sql = "select * from avaliacao";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchall(PDO::FETCH_ASSOC);
        return $resultado;
    }
    public function remover_questao($numero_questao){
        try {
            // Começar uma transação para garantir que todas as operações sejam executadas ou nenhuma
            $this->conexao->beginTransaction();
            
            // 1. Primeiro, remover referências na tabela questao_prova
            $sql = "delete from questao_prova where id_questao = :numero_questao";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":numero_questao", $numero_questao);
            $stmt->execute();

            // 2. Remover as alternativas
            $sql = "delete from alternativas where id_questao = :numero_questao";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":numero_questao", $numero_questao);
            $stmt->execute();

            // 3. Finalmente, remover a questão
            $sql = "delete from questao where id = :numero_questao";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":numero_questao", $numero_questao);
            $stmt->execute();

            // Se chegou até aqui sem erros, confirma as alterações
            $this->conexao->commit();
            return true;
        } catch (PDOException $e) {
            // Em caso de erro, desfaz todas as alterações
            $this->conexao->rollBack();
            return false;
        }
    }

    public function corrigir_prova($ano) {
        try {
            // Fetch students from the specified year
            $sql = "SELECT * FROM aluno WHERE ano = :ano ORDER BY nome ASC";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':ano', $ano);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array();
        }
    }

    public function get_avaliacoes_por_ano($ano) {
        try {
            // Debug
            error_log("Fetching evaluations for year: " . $ano);

            // First try exact match
            $sql = "SELECT * FROM avaliacao WHERE ano = :ano ORDER BY id DESC";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':ano', $ano);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // If no results, try with just the number
            if (empty($result) && preg_match('/(\d+)b/i', $ano, $matches)) {
                $yearNumber = $matches[1];
                error_log("No results with exact match, trying with year number: " . $yearNumber);
                
                $sql = "SELECT * FROM avaliacao WHERE ano LIKE :ano ORDER BY id DESC";
                $stmt = $this->conexao->prepare($sql);
                $searchPattern = $yearNumber . "%";
                $stmt->bindParam(':ano', $searchPattern);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            // Debug the SQL and results
            error_log("SQL Query: " . $sql);
            error_log("Found " . count($result) . " evaluations");
            error_log("Results: " . print_r($result, true));

            return $result;
        } catch (PDOException $e) {
            error_log("Error in get_avaliacoes_por_ano: " . $e->getMessage());
            return array();
        }
    }

    public function get_questoes_avaliacao($id_avaliacao) {
        try {
            $sql = "SELECT q.*, qp.id as questao_prova_id 
                   FROM questao q 
                   INNER JOIN questao_prova qp ON q.id = qp.id_questao 
                   WHERE qp.id_avaliacao = :id_avaliacao";
            
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':id_avaliacao', $id_avaliacao);
            $stmt->execute();
            
            $questoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get alternatives for each question
            foreach ($questoes as &$questao) {
                $sql = "SELECT * FROM alternativas WHERE id_questao = :id_questao ORDER BY id";
                $stmt = $this->conexao->prepare($sql);
                $stmt->bindParam(':id_questao', $questao['id']);
                $stmt->execute();
                $questao['alternativas'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            return $questoes;
        } catch (PDOException $e) {
            return array();
        }
    }

    public function salvar_correcao($id_aluno, $id_avaliacao, $respostas) {
        try {
            // Calculate total questions, correct and incorrect answers
            $total_questoes = count($respostas);
            $acertos = 0;
            $erros = 0;
            
            // Begin transaction
            $this->conexao->beginTransaction();
            
            // Prepare statement for inserting individual responses
            $sql_resposta = "INSERT INTO respostas_alunos (id_aluno, id_questao, id_avaliacao, resposta_aluno) 
                            VALUES (:id_aluno, :id_questao, :id_avaliacao, :resposta_aluno)";
            $stmt_resposta = $this->conexao->prepare($sql_resposta);

            // Get questions and their correct answers
            $questoes = $this->get_questoes_avaliacao($id_avaliacao);
            $questoes_corretas = [];
            foreach ($questoes as $questao) {
                foreach ($questao['alternativas'] as $alt) {
                    if ($alt['resposta'] === 'sim') {
                        $questoes_corretas[$questao['id']] = $alt['texto'];
                        break;
                    }
                }
            }
            
            // Process each response
            foreach ($respostas as $id_questao => $resposta_texto) {
                // Save individual response
                $stmt_resposta->execute([
                    ':id_aluno' => $id_aluno,
                    ':id_questao' => $id_questao,
                    ':id_avaliacao' => $id_avaliacao,
                    ':resposta_aluno' => $resposta_texto
                ]);
                
                // Check if response is correct
                if (isset($questoes_corretas[$id_questao]) && $resposta_texto === $questoes_corretas[$id_questao]) {
                    $acertos++;
                } else {
                    $erros++;
                }
            }
            
            // Calculate grade (0-10)
            $nota = ($acertos / $total_questoes) * 10;
            
            // Insert into relatorio_aluno
            $sql = "INSERT INTO relatorio_aluno (nota, acertos, erros, id_aluno, id_prova) 
                   VALUES (:nota, :acertos, :erros, :id_aluno, :id_prova)";
            
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':nota', $nota);
            $stmt->bindParam(':acertos', $acertos);
            $stmt->bindParam(':erros', $erros);
            $stmt->bindParam(':id_aluno', $id_aluno);
            $stmt->bindParam(':id_prova', $id_avaliacao);
            $stmt->execute();
            
            $this->conexao->commit();
            return true;
            
        } catch (PDOException $e) {
            error_log("Erro ao salvar correção: " . $e->getMessage());
            if ($this->conexao->inTransaction()) {
                $this->conexao->rollBack();
            }
            return false;
        }
    }

    public function buscar_questoes_avaliacao($id_avaliacao) {
        try {
            $sql = "SELECT q.* FROM questao q 
                   INNER JOIN questao_prova qp ON q.id = qp.id_questao 
                   WHERE qp.id_avaliacao = :id_avaliacao";
            
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':id_avaliacao', $id_avaliacao);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return array();
        }
    }

    public function buscar_alternativas_questao($id_questao) {
        try {
            $sql = "SELECT * FROM alternativas WHERE id_questao = :id_questao ORDER BY id";
            
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':id_questao', $id_questao);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return array();
        }
    }

    public function adicionar_subtopico($disciplina, $nome) {
        try {
            $sql = "INSERT INTO subtopicos (disciplina, nome) VALUES (:disciplina, :nome)";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':disciplina', $disciplina);
            $stmt->bindParam(':nome', $nome);
            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    public function excluir_subtopico($id) {
        try {
            $sql = "DELETE FROM subtopicos WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    public function listar_subtopicos() {
        try {
            $sql = "SELECT * FROM subtopicos ORDER BY disciplina, nome";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return array();
        }
    }

    public function get_subtopicos_por_disciplina($disciplina) {
        try {
            $sql = "SELECT * FROM subtopicos WHERE disciplina = :disciplina ORDER BY nome";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':disciplina', $disciplina);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return array();
        }
    }

    public function get_subtopico_by_id($id) {
        try {
            $sql = "SELECT * FROM subtopicos WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return null;
        }
    }

    public function atualizar_status_questao($id_questao) {
        try {
            $sql = "UPDATE questao SET status = 1 WHERE id = :id_questao";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':id_questao', $id_questao);
            $stmt->execute();

            return true;
        } catch(PDOException $e) {
            return false;
        }
    }

    public function zerar_status_questoes($disciplina = null, $subtopico = null, $questoes = null) {
        try {
            // Se questões específicas foram fornecidas
            if (is_array($questoes) && !empty($questoes)) {
                $placeholders = str_repeat('?,', count($questoes) - 1) . '?';
                $sql = "UPDATE questao SET status = 0 WHERE id IN ($placeholders)";
                $stmt = $this->conexao->prepare($sql);
                $stmt->execute($questoes);
                return true;
            }

            // Caso contrário, usar os filtros
            $sql = "UPDATE questao SET status = 0 WHERE 1=1";
            $params = array();

            if (!empty($disciplina)) {
                $sql .= " AND disciplina = :disciplina";
                $params[':disciplina'] = $disciplina;
            }

            if (!empty($subtopico)) {
                $sql .= " AND subtopico = :subtopico";
                $params[':subtopico'] = $subtopico;
            }

            $stmt = $this->conexao->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();

            return true;
        } catch(PDOException $e) {
            return false;
        }
    }

    public function acessar_banco_status($disciplina = null, $subtopico = null) {
        try {
            // Construir a consulta base para buscar apenas questões com status = 1
            $sql = "SELECT * FROM questao WHERE status = 1";
            $params = array();

            // Adicionar condições apenas se os parâmetros não estiverem vazios
            if (!empty($disciplina)) {
                $sql .= " AND disciplina = :disciplina";
                $params[':disciplina'] = $disciplina;
            }

            if (!empty($subtopico)) {
                $sql .= " AND subtopico = :subtopico";
                $params[':subtopico'] = $subtopico;
            }

            $stmt = $this->conexao->prepare($sql);
            
            // Vincular os parâmetros
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e) {
            return array();
        }
    }

    public function get_questao_by_id($id) {
        try {
            // Get the question
            $sql = "SELECT q.*, 
                   (SELECT texto FROM alternativas WHERE id_questao = q.id AND resposta = 'sim' LIMIT 1) as resposta_correta,
                   (SELECT texto FROM alternativas WHERE id_questao = q.id ORDER BY id ASC LIMIT 1) as alternativaA,
                   (SELECT texto FROM alternativas WHERE id_questao = q.id ORDER BY id ASC LIMIT 1 OFFSET 1) as alternativaB,
                   (SELECT texto FROM alternativas WHERE id_questao = q.id ORDER BY id ASC LIMIT 1 OFFSET 2) as alternativaC,
                   (SELECT texto FROM alternativas WHERE id_questao = q.id ORDER BY id ASC LIMIT 1 OFFSET 3) as alternativaD
                   FROM questao q WHERE q.id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return null;
        }
    }

    public function atualizar_questao($id, $disciplina, $subtopico, $dificuldade, $enunciado, $alternativaA, $alternativaB, $alternativaC, $alternativaD, $resposta_correta) {
        try {
            // Start transaction
            $this->conexao->beginTransaction();
            
            // Update question
            $sql = "UPDATE questao SET 
                    disciplina = :disciplina,
                    subtopico = :subtopico,
                    grau_de_dificuldade = :dificuldade,
                    enunciado = :enunciado
                    WHERE id = :id";
                    
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':disciplina', $disciplina);
            $stmt->bindParam(':subtopico', $subtopico);
            $stmt->bindParam(':dificuldade', $dificuldade);
            $stmt->bindParam(':enunciado', $enunciado);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            // Delete existing alternatives
            $sql = "DELETE FROM alternativas WHERE id_questao = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            // Insert new alternatives
            $alternativas = [
                'A' => $alternativaA,
                'B' => $alternativaB,
                'C' => $alternativaC,
                'D' => $alternativaD
            ];
            
            foreach ($alternativas as $letra => $texto) {
                $resposta = ($letra === $resposta_correta) ? 'sim' : 'nao';
                $sql = "INSERT INTO alternativas (id_questao, texto, resposta) VALUES (:id_questao, :texto, :resposta)";
                $stmt = $this->conexao->prepare($sql);
                $stmt->bindParam(':id_questao', $id);
                $stmt->bindParam(':texto', $texto);
                $stmt->bindParam(':resposta', $resposta);
                $stmt->execute();
            }
            
            // Commit transaction
            $this->conexao->commit();
            return true;
            
        } catch(PDOException $e) {
            // Rollback on error
            if ($this->conexao->inTransaction()) {
                $this->conexao->rollBack();
            }
            return false;
        }
    }

    public function get_relatorios_aluno($id_aluno) {
        try {
            $sql = "SELECT r.*, a.nome as nome_avaliacao, a.tipo, a.dificuldade 
                   FROM relatorio_aluno r 
                   INNER JOIN avaliacao a ON r.id_prova = a.id 
                   WHERE r.id_aluno = :id_aluno 
                   ORDER BY r.id DESC";
            
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':id_aluno', $id_aluno);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in get_relatorios_aluno: " . $e->getMessage());
            return array();
        }
    }

    public function get_detalhes_relatorio($id_relatorio) {
        try {
            // Get report details
            $sql = "SELECT r.*, a.nome as nome_avaliacao, a.tipo, a.dificuldade,
                          al.nome as nome_aluno, al.matricula
                   FROM relatorio_aluno r 
                   INNER JOIN avaliacao a ON r.id_prova = a.id
                   INNER JOIN aluno al ON r.id_aluno = al.id 
                   WHERE r.id = :id_relatorio";
            
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':id_relatorio', $id_relatorio);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in get_detalhes_relatorio: " . $e->getMessage());
            return null;
        }
    }

    public function get_all_students() {
        try {
            $sql = "SELECT a.*, 
                   COUNT(DISTINCT r.id) as total_avaliacoes,
                   AVG(r.nota) as media_notas
                   FROM aluno a 
                   LEFT JOIN relatorio_aluno r ON a.id = r.id_aluno 
                   GROUP BY a.id 
                   ORDER BY a.nome";
            
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in get_all_students: " . $e->getMessage());
            return array();
        }
    }

    public function get_respostas_alunos($id_aluno, $id_avaliacao) {
        try {
            $sql = "SELECT ra.id_questao, 
                           CASE 
                               WHEN ra.resposta_aluno = a.texto AND a.resposta = 'sim' THEN 1
                               ELSE 0
                           END as esta_correta
                    FROM respostas_alunos ra
                    JOIN questao_prova qp ON ra.id_questao = qp.id_questao
                    JOIN alternativas a ON ra.id_questao = a.id_questao AND a.resposta = 'sim'
                    WHERE ra.id_aluno = :id_aluno 
                    AND qp.id_avaliacao = :id_avaliacao";

            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':id_aluno', $id_aluno);
            $stmt->bindParam(':id_avaliacao', $id_avaliacao);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar respostas dos alunos: " . $e->getMessage());
            return array();
        }
    }

    public function get_questao_stats($id_avaliacao) {
        try {
            $sql = "SELECT 
                    q.id,
                    q.enunciado,
                    COUNT(DISTINCT ra.id_aluno) as total_respostas,
                    SUM(CASE WHEN ra.resposta_aluno != alt.texto AND alt.resposta = 'sim' THEN 1 ELSE 0 END) as total_erros
                FROM questao q
                JOIN questao_prova qp ON q.id = qp.id_questao
                JOIN respostas_alunos ra ON q.id = ra.id_questao
                JOIN alternativas alt ON q.id = alt.id_questao AND alt.resposta = 'sim'
                WHERE qp.id_avaliacao = :id_avaliacao
                GROUP BY q.id, q.enunciado
                ORDER BY total_erros DESC";

            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':id_avaliacao', $id_avaliacao);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar estatísticas das questões: " . $e->getMessage());
            return array();
        }
    }

}


?>
 