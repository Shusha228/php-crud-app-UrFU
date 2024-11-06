<?php

declare(strict_types=1);

namespace app\controller;

use app\service\MessageService;
use app\service\UserService;
use app\core\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class MessageController{

	private Session $session;

	public function __construct(
		private MessageService $messageService,
		private UserService $userService,
		Session $session
	){
		$this->session = $session;
	}

	public function showMessages(Request $request) : void{
		$userId = $this->session->get('user_id');

		if(!$userId){
			View::redirect('/login');
			return;
		}

		$user = $this->userService->findById($userId);
		$userName = $user->name;

		$messages = $this->messageService->getMessages();

		View::render('wall', [
			'userName' => $userName,
			'userId' => $userId,
			'messages' => $messages
		]);
	}

	public function createMessage(Request $request) : void{
		$userId = $this->session->get('user_id');

		if(!$userId){
			View::redirect('/login');
			return;
		}

		$content = $request->request->get('content');
		$this->messageService->createMessage($userId, $content);
		View::redirect('/wall');

	}

	public function editMessage(Request $request, int $messageId) : void{
		$userId = $this->session->get('user_id');

		if(!$userId){
			View::redirect('/login');
			return;
		}

		$content = $request->request->get('content');
		$this->messageService->updateMessage($messageId, $userId, $content);
		View::redirect('/wall');
	}

	public function deleteMessage(int $messageId) : void{
		$userId = $this->session->get('user_id');

		if(!$userId){
			View::redirect('/login');
			return;
		}

		$this->messageService->deleteMessage($messageId, $userId);
		View::redirect('/wall');

	}
}
