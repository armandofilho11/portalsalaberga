<?php
class connect 
{
    protected $conn;

    function __construct() 
    {
        $this->connect_database();
    }

    function connect_database()
    {
        date_default_timezone_set('America/Sao_Paulo');
        $conexoes = [];

        $configArquivo = dirname(__DIR__, 2) . '/.env/config.php';
        $config = is_file($configArquivo) ? require $configArquivo : [];

        // Em produção, configure DB_HOST, DB_NAME, DB_USER e DB_PASSWORD na hospedagem.
        $configHospedagem = is_array($config['host'] ?? null) ? $config['host'] : [];
        $host = getenv('DB_HOST') ?: ($configHospedagem['host'] ?? '');
        $database = getenv('DB_NAME') ?: ($configHospedagem['database'] ?? '');
        $user = getenv('DB_USER') ?: ($configHospedagem['user'] ?? '');
        $password = getenv('DB_PASSWORD') ?: ($configHospedagem['password'] ?? '');
        if ($database || $user) {
            $conexoes[] = [
                $host ?: 'localhost',
                $database,
                $user,
                $password,
            ];
        }

        // Compatibilidade com o ambiente local e com a instalação existente.
        $conexoes[] = ['localhost', 'sistema_de_demandas', 'root', ''];
        $conexoes[] = ['localhost', 'u750204740_demandas', 'u750204740_demandas', 'Demandas!@#$2026'];

        $ultimoErro = null;
        foreach ($conexoes as [$host, $database, $user, $password]) {
            if ($database === '' || $user === '') {
                continue;
            }

            try {
                $this->conn = new PDO(
                    'mysql:host=' . $host . ';dbname=' . $database . ';charset=utf8mb4',
                    $user,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4, time_zone = '-03:00'",
                    ]
                );
                return;
            } catch (PDOException $e) {
                $ultimoErro = $e;
            }
        }

        error_log('Falha ao conectar ao banco de dados: ' . ($ultimoErro ? $ultimoErro->getMessage() : 'configuração ausente'));
        throw new RuntimeException('Não foi possível conectar ao banco de dados.');
    }

    function getConnection() 
    {
        return $this->conn;
    }
}

?>