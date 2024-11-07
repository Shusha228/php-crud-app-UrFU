<?php

declare(strict_types=1);

namespace app\core;

class View{

	/**
	 * Рендерит указанный шаблон и передает данные в него.
	 *
	 * @param string $template Название шаблона
	 * @param array $data Массив данных для передачи в шаблон (array<string, string>)
	 */
	public static function render(string $template, array $data = []) : void{
		extract($data);

		$templatePath = __DIR__ . '/../../views/' . $template . '.php';

		if(!file_exists($templatePath)){
			echo "Невозможно отрендерить страницу. Шаблон не найден: $templatePath";
			return;
		}
		ob_start();
		include $templatePath;
		echo ob_get_clean();
	}

	public static function redirect(string $url) : void{
		header('Location: ' . $url);
	}
}