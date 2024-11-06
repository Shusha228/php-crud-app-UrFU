<?php

declare(strict_types=1);

namespace app\repository\message;

use app\repository\user\UserRepositoryInterface;
use DateTime;
use PDO;
use app\dto\MessageDTO;

class MySQLMessageRepository implements MessageRepositoryInterface{

	public function __construct(
		private PDO $pdo,
		private UserRepositoryInterface $userRepository
	){
	}

	public function createMessage(int $userId, string $content) : MessageDTO{
		$stmt = $this->pdo->prepare("INSERT INTO messages (user_id, content, created_at, author) VALUES (:user_id, :content, NOW(), :author)");
		$stmt->execute([
			':user_id' => $userId,
			':content' => $content,
			':author' => $this->userRepository->findUserById($userId)->name
		]);

		$messageId = $this->pdo->lastInsertId();

		return new MessageDTO((int) $messageId, $userId, $content, date("d-m-Y H:i:s"), $this->userRepository->findUserById($userId)->name, 'Unknown');
	}

	public function getMessages() : array{
		$stmt = $this->pdo->query("SELECT m.id, m.content, m.created_at, m.user_id, u.name AS author FROM messages m JOIN users u ON m.user_id = u.id ORDER BY m.created_at DESC");

		$messages = [];
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$messages[] = new MessageDTO(
				$row['id'],
				$row['user_id'],
				$row['content'],
				DateTime::createFromFormat("Y-m-d H:i:s", $row['created_at'])->format('d-m-Y H:i:s'),
				$row['author']
			);
		}

		return $messages;
	}

	public function getMessageById(int $messageId) : ?MessageDTO{
		$stmt = $this->pdo->prepare("SELECT m.id, m.content, m.created_at, m.user_id, u.name AS author FROM messages m JOIN users u ON m.user_id = u.id WHERE m.id = :id");
		$stmt->execute([':id' => $messageId]);

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if($row){
			return new MessageDTO(
				$row['id'],
				$row['user_id'],
				$row['content'],
				$row['created_at'],
				$row['author']
			);
		}
		return null;
	}

	public function updateMessage(int $messageId, string $content) : bool{
		$stmt = $this->pdo->prepare("UPDATE messages SET content = :content WHERE id = :id");
		return $stmt->execute([':content' => $content, ':id' => $messageId]);
	}

	public function deleteMessage(int $messageId) : bool{
		$stmt = $this->pdo->prepare("DELETE FROM messages WHERE id = :id");
		return $stmt->execute([':id' => $messageId]);
	}
}
