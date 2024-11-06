<?php

declare(strict_types=1);

namespace app\repository\user;

use app\dto\UserDTO;
use PDO;

class MySQLUserRepository implements UserRepositoryInterface{

	public function __construct(
		private PDO $db
	){
	}

	public function createUser(string $name, string $email, string $password) : UserDTO{
		$stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
		$stmt->execute([$name, $email, password_hash($password, PASSWORD_BCRYPT)]);

		return new UserDTO(
			(int) $this->db->lastInsertId(),
			$name,
			$email,
			$password
		);
	}

	public function findUserByEmail(string $email) : ?UserDTO{
		$stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
		$stmt->execute([$email]);
		$user = $stmt->fetch();

		return $user ? new UserDTO($user['id'], $user['name'], $user['email'], $user['password']) : null;
	}

	public function findUserById(int $id) : ?UserDTO{
		$stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
		$stmt->execute([$id]);
		$user = $stmt->fetch();

		return $user ? new UserDTO($id, $user['name'], $user['email'], $user['password']) : null;
	}
}
