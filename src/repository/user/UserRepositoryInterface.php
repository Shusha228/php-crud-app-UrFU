<?php

declare(strict_types=1);

namespace app\repository\user;

use app\dto\UserDTO;

interface UserRepositoryInterface{

	public function createUser(string $name, string $email, string $password) : UserDTO;

	public function findUserByEmail(string $email) : ?UserDTO;

	public function findUserById(int $id) : ?UserDTO;
}
