<?php

class ConcedenteController {
    private $conn;

    public function __construct() {
        $this->connectDB();
    }

    private function connectDB() {
        // Configurações de conexão na hospedagem
        $servername = "localhost";
        $username = "u750204740_form_concedent";
        $password = "paoComOvo123!@##";
        $dbname = "u750204740_form_concedent";

        // Tentar conexão
        $this->conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar conexão
        if ($this->conn->connect_error) {
            // Logar erro (opcional: salvar em um arquivo de log)
            error_log("Erro na conexão: " . $this->conn->connect_error);
            die("Erro ao conectar ao banco de dados. Por favor, tente novamente mais tarde.");
        }

        // Definir charset para evitar problemas com codificação
        $this->conn->set_charset("utf8mb4");
    }

    public function inserirConcedente($dados) {
        // Preparar a consulta
        $stmt = $this->conn->prepare("INSERT INTO concedentes (tipo_instituicao, rede, razao_social, nome_fantasia, cnpj, telefone_institucional, especificacao_atividade, email_institucional, nome_representante, email_representante, cpf_representante, rg_representante, nome_supervisor, email_supervisor, celular_supervisor, whats_do_supervisor, endereco, numero, complemento, cep, bairro, municipio, observacoes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        if (!$stmt) {
            error_log("Erro ao preparar a consulta: " . $this->conn->error);
            die("Erro ao preparar a consulta. Por favor, tente novamente.");
        }

        // Vincular parâmetros
        $stmt->bind_param("sssssssssssssssssssssss",
            $dados['tipo_instituicao'],
            $dados['rede'],
            $dados['razao_social'],
            $dados['nome_fantasia'],
            $dados['cnpj'],
            $dados['telefone_institucional'],
            $dados['especificacao_atividade'],
            $dados['email_institucional'],
            $dados['nome_representante'],
            $dados['email_representante'],
            $dados['cpf_representante'],
            $dados['rg_representante'],
            $dados['nome_supervisor'],
            $dados['email_supervisor'],
            $dados['celular_supervisor'],
            $dados['whats_do_supervisor'],
            $dados['endereco'],
            $dados['numero'],
            $dados['complemento'],
            $dados['cep'],
            $dados['bairro'],
            $dados['municipio'],
            $dados['observacoes']
        );

        // Executar a consulta
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: ../../views/success.php");
            exit(); // Garantir que o script pare após o redirecionamento
        } else {
            error_log("Erro ao executar a consulta: " . $stmt->error);
            echo "Erro ao inserir os dados: " . $stmt->error;
        }

        $stmt->close();
    }

    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}

// Exemplo de uso
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $controller = new ConcedenteController();
        $controller->inserirConcedente($_POST);
    } catch (Exception $e) {
        error_log("Exceção capturada: " . $e->getMessage());
        echo "Ocorreu um erro. Por favor, tente novamente.";
    }
}

?>