<?php

declare(strict_types=1);

namespace app\dto;

readonly class MessageDTO{

	public function __construct(
		public int $id,
		public int $user_id,
		public string $content,
		public string $created_at,
		public string $author
	){
	}
}
