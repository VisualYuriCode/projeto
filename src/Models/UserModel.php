<?php

namespace App\Models;

use Core\Database;
use PDO;

class UserModel
{
    private ?PDO $conn;

    public function __construct()
    {
        // É AQUI QUE INSTANCIAMOS A BASE DE DADOS!
        // Sempre que o UserModel for chamado, ele liga-se automaticamente ao MySQL.
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    /**
     * Procura um utilizador na base de dados através do e-mail.
     * Útil para o processo de Login.
     */
    public function findByEmail(string $email)
    {
        // Usamos Prepared Statements (:email) para evitar SQL Injection
        $query = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Retorna o objeto do utilizador se encontrar, ou 'false' se não existir
        return $stmt->fetch();
    }

    /**
     * Insere um novo utilizador na base de dados.
     * Útil para o processo de Criar Conta.
     */
    public function create(string $nome, string $email, string $senha): bool
    {
        // NUNCA guardamos a palavra-passe em texto limpo! Geramos um Hash seguro.
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $query = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senhaHash);

        return $stmt->execute();
    }
}