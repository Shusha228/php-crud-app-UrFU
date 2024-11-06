<?php

declare(strict_types=1);

namespace app\repository\message;

use app\dto\MessageDTO;

interface MessageRepositoryInterface{

	public function createMessage(int $userId, string $content) : MessageDTO;

	public function getMessages() : array;

	public function getMessageById(int $messageId) : ?MessageDTO;

	public function updateMessage(int $messageId, string $content) : bool;

	public function deleteMessage(int $messageId) : bool;
}
