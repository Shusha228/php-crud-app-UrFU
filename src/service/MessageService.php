<?php

declare(strict_types=1);

namespace app\service;

use app\repository\message\MessageRepositoryInterface;
use app\dto\MessageDTO;

class MessageService{

	public function __construct(
		private MessageRepositoryInterface $messageRepository
	){
	}

	public function createMessage(int $userId, string $content) : MessageDTO{
		return $this->messageRepository->createMessage($userId, $content);
	}

	public function getMessages() : array{
		return $this->messageRepository->getMessages();
	}

	public function getMessageById(int $messageId) : ?MessageDTO{
		return $this->messageRepository->getMessageById($messageId);
	}

	public function updateMessage(int $messageId, int $userId, string $content) : bool{
		$message = $this->messageRepository->getMessageById($messageId);
		if($message && $message->user_id === $userId){
			return $this->messageRepository->updateMessage($messageId, $content);
		}
		return false;
	}

	public function deleteMessage(int $messageId, int $userId) : bool{
		$message = $this->messageRepository->getMessageById($messageId);
		if($message && $message->user_id === $userId){
			return $this->messageRepository->deleteMessage($messageId);
		}
		return false;
	}
}
