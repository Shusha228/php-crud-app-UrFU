<?php

declare(strict_types=1);

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

return [
	'host' => $_ENV['DB_HOST'],
	'dbname' => $_ENV['DB_NAME'],
	'user' => $_ENV['DB_USER'],
	'password' => $_ENV['DB_PASS']
];
