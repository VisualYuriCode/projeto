<?php

namespace Core;

use PDO;
use PDOException;

class Database
{
    // Credenciais padrão do Laragon
    private string $host = '127.0.0.1';
    private string $db_name = 'php_login_mvc';
    private string $username = 'root';
    private string $password = ''; // Laragon vem sem senha por padrão
    private ?PDO $conn = null;

    // Método responsável por estabelecer a conexão
    public function getConnection(): ?PDO
    {
        $this->conn = null;

        try {
            // Cria a string de conexão (DSN)
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            
            // Instancia o objeto PDO
            $this->conn = new PDO($dsn, $this->username, $this->password);
            
            // Configura o PDO para lançar exceções caso ocorra algum erro no SQL
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Configura o retorno padrão dos dados como Objetos (facilita a leitura)
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        } catch (PDOException $exception) {
            // Se o Laragon estiver desligado ou o banco não existir, ele avisa aqui
            echo "Erro de Conexão com o Banco de Dados: " . $exception->getMessage();
            exit;
        }

        return $this->conn;
    }
}