<?php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Encryptor.php';

class User
{
    private PDO $conn;
    private Encryptor $encryptor;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
        $this->encryptor = new Encryptor();
    }

    public function register(string $username, string $password): bool
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Sukuriamas unikalus vartotojo raktas
        $plainKey = bin2hex(random_bytes(16));

        // Raktas užkoduojamas AES metodu naudojant vartotojo slaptažodį
        $encryptedKey = $this->encryptor->encrypt($plainKey, $password);

        $sql = "INSERT INTO users (username, password_hash, encrypted_key) 
                VALUES (:username, :password_hash, :encrypted_key)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':username' => $username,
            ':password_hash' => $passwordHash,
            ':encrypted_key' => $encryptedKey
        ]);
    }

    public function usernameExists(string $username): bool
    {
        $sql = "SELECT id FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':username' => $username]);

        return $stmt->rowCount() > 0;
    }

    public function login(string $username, string $password): array|false
    {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':username' => $username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }

        return false;
    }
}