<?php
class connect
{
    protected $connect;
    protected $connect_users;

    function __construct()
    {
        $this->connect_database();
    }

    function connect_database()
    {
        $config = require(__DIR__ . "/../../../../.env/config.php");
        $tables = require(__DIR__ . "/../../../../.env/tables.php");
        $requiredStockTables = array_values($tables['salaberga_estoque']);
        $requiredUsersTables = [
            $tables['salaberga_users'][1],
            $tables['salaberga_users'][2],
        ];

        foreach (['local', 'hospedagem'] as $environment) {
            if (
                !isset($config[$environment]['salaberga_estoque']) ||
                !isset($config[$environment]['salaberga_users'])
            ) {
                continue;
            }

            try {
                $stockConnection = $this->openConnection($config[$environment]['salaberga_estoque']);
                $this->assertTablesExist($stockConnection, $requiredStockTables);

                $usersConnection = $this->openConnection($config[$environment]['salaberga_users']);
                $this->assertTablesExist($usersConnection, $requiredUsersTables);

                $this->connect = $stockConnection;
                $this->connect_users = $usersConnection;
                return;
            } catch (PDOException $e) {
                error_log("Banco de estoque indisponivel no ambiente {$environment}: " . $e->getMessage());
            }
        }

        $this->connect = null;
        $this->connect_users = null;
        header('location:../views/windows/desconnect.php');
        exit();
    }

    private function openConnection(array $databaseConfig): PDO
    {
        $dsn = 'mysql:host=' . $databaseConfig['host'] . ';dbname=' . $databaseConfig['banco'] . ';charset=utf8mb4';
        $connection = new PDO($dsn, $databaseConfig['user'], $databaseConfig['senha']);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $connection;
    }

    private function assertTablesExist(PDO $connection, array $requiredTables): void
    {
        $statement = $connection->prepare(
            'SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = :table_name'
        );

        foreach ($requiredTables as $tableName) {
            $statement->execute(['table_name' => $tableName]);

            if ((int) $statement->fetchColumn() !== 1) {
                throw new PDOException("Tabela obrigatoria ausente: {$tableName}");
            }
        }
    }
}
