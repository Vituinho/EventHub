<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() {

		$routes['cadastro'] = array(
			'route' => '/cadastro',
			'controller' => 'IndexController',
			'action' => 'Cadastro'
		);

		$routes['login'] = array(
			'route' => '/',
			'controller' => 'IndexController',
			'action' => 'Login'
		);

		$routes['home'] = array(
			'route' => '/home',
			'controller' => 'IndexController',
			'action' => 'Home'
		);

		$routes['novoUsuario'] = array(
			'route' => '/usuario/salvar',
			'controller' => 'IndexController',
			'action' => 'NovoUsuario'
		);

		$routes['cadastro_eventos'] = array(
			'route' => '/eventos/cadastrar',
			'controller' => 'IndexController',
			'action' => 'CadastroEventos'
		);

		$routes['sobre'] = array(
			'route' => '/eventos/sobre',
			'controller' => 'IndexController',
			'action' => 'Sobre'
		);

		$routes['sobre'] = array(
			'route' => '/login/autenticar',
			'controller' => 'IndexController',
			'action' => 'autenticar'
		);

		$this->setRoutes($routes);
	}

}

?>