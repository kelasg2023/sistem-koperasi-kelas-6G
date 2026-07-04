<?php

namespace App\Services;

use PDO;
use PDOException;

class AuthService
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function register(array $data): array
    {
        $username = trim((string) ($data['username'] ?? ''));
        $password = (string) ($data['password'] ?? '');
        $role = $data['role'] ?? 'staff';

        if ($username === '' || $password === '') {
            return ['success' => false, 'message' => 'Username dan password wajib diisi'];
        }

        if ($this->userExists($username)) {
            return ['success' => false, 'message' => 'Username sudah terdaftar'];
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, ?)');
            $stmt->execute([$username, $hashedPassword, $role]);
            $userId = (int) $this->pdo->lastInsertId();

            // PERBAIKAN 1: Ubah nama kolom 'member' menjadi 'is_member'
            $profileStmt = $this->pdo->prepare('INSERT INTO users_profiles (user_id, name, address, profile_picture, phone, is_member) VALUES (?, ?, ?, ?, ?, ?)');
            
            // PERBAIKAN 2: Tangkap data checkbox 'register_as_member' dari frontend (1 jika dicentang, 0 jika tidak)
            $isMember = !empty($data['register_as_member']) ? 1 : 0;

            $profileStmt->execute([
                $userId,
                $data['name'] ?? '',
                $data['address'] ?? '',
                $data['profile_picture'] ?? '',
                $data['phone'] ?? '',
                $isMember, // Gunakan variabel $isMember yang sudah difilter
            ]);

            $this->pdo->commit();

            return ['success' => true, 'message' => 'Register berhasil', 'user_id' => $userId];
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return ['success' => false, 'message' => 'Register gagal: ' . $e->getMessage()];
        }
    }

    public function login(string $username, string $password): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Username atau password salah'];
        }

        $profile = $this->getProfile((int) $user['id_users']);

        return [
            'success' => true,
            'message' => 'Login berhasil',
            'user' => [
                'id_users' => (int) $user['id_users'],
                'username' => $user['username'],
                'role' => $user['role'],
                'profile' => $profile,
            ],
        ];
    }

    public function forgotPassword(string $username, string $newPassword): array
    {
        if ($username === '' || $newPassword === '') {
            return ['success' => false, 'message' => 'Username dan password baru wajib diisi'];
        }

        $stmt = $this->pdo->prepare('SELECT id_users FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return ['success' => false, 'message' => 'Username tidak ditemukan'];
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        $updateStmt = $this->pdo->prepare('UPDATE users SET password = ? WHERE id_users = ?');
        $updateStmt->execute([$hashedPassword, $user['id_users']]);

        return ['success' => true, 'message' => 'Password berhasil direset'];
    }

    private function userExists(string $username): bool
    {
        $stmt = $this->pdo->prepare('SELECT 1 FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        return (bool) $stmt->fetchColumn();
    }

    private function getProfile(int $userId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users_profiles WHERE user_id = ? LIMIT 1');
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }
}