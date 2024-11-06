<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use app\controller\AuthController;
use app\controller\MessageController;
use app\repository\user\MySQLUserRepository;
use app\repository\message\MySQLMessageRepository;
use app\service\UserService;
use app\service\MessageService;
use app\core\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

$config = require __DIR__ . '/../config/database.php';
$pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']}", $config['user'], $config['password']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$session = new Session();
$session->start();

$userRepository = new MySQLUserRepository($pdo);
$messageRepository = new MySQLMessageRepository($pdo, $userRepository);

$userService = new UserService($userRepository);
$messageService = new MessageService($messageRepository);

$authController = new AuthController($userService, $session);
$messageController = new MessageController($messageService, $userService, $session);

$request = Request::createFromGlobals();

$path = $request->getPathInfo();
$method = $request->getMethod();

switch($path){
	case '/register':
		if($method === 'POST'){
			$authController->register($request);
		}else{
			if($session->get('user_id')){
				View::redirect('/wall');
				return;
			}
			View::render('register');
		}
		break;

	case '/login':
		if($method === 'POST'){
			$authController->login($request);
		}else{
			if($session->get('user_id') !== null){
				View::redirect('/wall');
				return;
			}
			View::render('login');
		}
		break;

	case '/logout':
		$authController->logout();
		break;

	case '/wall':
		$messageController->showMessages($request);
		break;

	case '/message/create':
		if($session->get('user_id')){
			if($method === 'POST'){
				$messageController->createMessage($request);
			}else{
				View::render('create_message');
			}
		}else{
			header('Location: /login');
			exit;
		}
		break;

	case '/message/edit':
		if($session->get('user_id')){
			$messageId = $request->request->get('id');
			$messageController->editMessage($request, (int) $messageId);
		}else{
			header('Location: /login');
			exit;
		}
		break;

	case '/message/delete':
		if($session->get('user_id')){
			$messageId = $request->query->get('id');
			$messageController->deleteMessage((int) $messageId);
		}else{
			header('Location: /login');
			exit;
		}
		break;

	default:
		echo "404 Not Found";
		break;
}
