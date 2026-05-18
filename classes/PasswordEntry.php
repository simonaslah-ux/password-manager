<?php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Encryptor.php';

class PasswordEntry
{
    private PDO $conn;
    private Encryptor $encryptor;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
        $this->encryptor = new Encryptor();
    }

    public function savePassword(int $userId, string $title, string $password, string $plainLoginPassword): bool
    {
        $userKey = $this->getUserKey($userId, $plainLoginPassword);

        if (!$userKey) {
            return false;
        }

        $encryptedPassword = $this->encryptor->encrypt($password, $userKey);

        $sql = "INSERT INTO passwords (user_id, title, encrypted_password)
                VALUES (:user_id, :title, :encrypted_password)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':user_id' => $userId,
            ':title' => $title,
            ':encrypted_password' => $encryptedPassword
        ]);
    }

    public function getUserPasswords(int $userId, string $plainLoginPassword): array
    {
        $userKey = $this->getUserKey($userId, $plainLoginPassword);

        if (!$userKey) {
            return [];
        }

        $sql = "SELECT * FROM passwords 
                WHERE user_id = :user_id 
                ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);

        $passwords = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($passwords as &$item) {
            $item['decrypted_password'] = $this->encryptor->decrypt(
                $item['encrypted_password'],
                $userKey
            );
        }

        return $passwords;
    }

    private function getUserKey(int $userId, string $plainLoginPassword): string|false
    {
        $sql = "SELECT encrypted_key FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $userId]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        return $this->encryptor->decrypt($user['encrypted_key'], $plainLoginPassword);
    }
}