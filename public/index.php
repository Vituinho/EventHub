<?php

	// Autoload do Composer
	require_once "../vendor/autoload.php";

	// 🔹 Função para carregar variáveis do .env
	function carregarEnv($arquivo) {
		if (!file_exists($arquivo)) return;

		$linhas = file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		foreach ($linhas as $linha) {
			if (strpos(trim($linha), '#') === 0) continue; // ignora comentários
			list($chave, $valor) = explode('=', $linha, 2);
			$chave = trim($chave);
			$valor = trim($valor);
			putenv("$chave=$valor"); // adiciona ao ambiente
		}
	}

	// 🔹 Caminho do .env (na raiz do projeto)
	$envPath = __DIR__ . '/../.env';

	// 🔹 Carrega o .env antes de rodar qualquer coisa
	carregarEnv($envPath);

	// 🔹 Agora sim pode inicializar as rotas
	$route = new \App\Route;

?>