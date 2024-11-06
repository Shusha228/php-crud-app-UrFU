<?php

declare(strict_types=1);

namespace app\controller;

use app\service\UserService;
use app\core\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class AuthController{

	private Session $session;

	public function __construct(
		private readonly UserService $authService,
		Session $session
	){
		$this->session = $session;
	}

	public function register(Request $request) : void{
		if($request->isMethod('POST')){
			$name = $request->get('name');
			$email = $request->get('email');
			$password = $request->get('password');
			$userDTO = $this->authService->register($name, $email, $password);
			$this->session->set('user_id', $userDTO->id);
			View::redirect('/wall');
			return;
		}

		View::render('register');
	}

	public function login(Request $request) : void{
		if($request->isMethod('POST')){
			$email = $request->get('email');
			$password = $request->get('password');
			$user = $this->authService->login($email, $password);

			if($user === null){
				View::render('login', ['error' => 'Неверный логин или пароль!']);
				return;
			}

			$this->session->set('user_id', $user->id);
			View::redirect('/wall');
			return;
		}

		View::render('login');
	}

	public function logout() : void{
		$this->session->remove('user_id');
		View::redirect('/login');
	}
}
