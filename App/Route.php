<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() {

		$routes['cadastro'] = array(
			'route' => '/cadastro',
			'controller' => 'LoginController',
			'action' => 'Cadastro'
		);

		$routes['login'] = array(
			'route' => '/',
			'controller' => 'LoginController',
			'action' => 'Login'
		);

		$routes['home'] = array(
			'route' => '/home',
			'controller' => 'LoginController',
			'action' => 'Home'
		);

		$routes['novoUsuario'] = array(
			'route' => '/usuario/salvar',
			'controller' => 'LoginController',
			'action' => 'NovoUsuario'
		);

		$routes['cadastro_eventos'] = array(
			'route' => '/eventos/cadastrar',
			'controller' => 'EventosController',
			'action' => 'CadastroEventos'
		);

		$routes['sobre'] = array(
			'route' => '/eventos/sobre',
			'controller' => 'LoginController',
			'action' => 'Sobre'
		);

		$routes['autenticar'] = array(
			'route' => '/login/autenticar',
			'controller' => 'IndexController',
			'action' => 'autenticar'
		);

		$this->setRoutes($routes);
	}

}

?>