<?php

declare(strict_types=1);

namespace app\dto;

readonly class UserDTO{

	public function __construct(
		public int $id,
		public string $name,
		public string $email,
		public string $password
	){
	}
}
