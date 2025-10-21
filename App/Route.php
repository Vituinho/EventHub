<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() {

		$routes['cadastro'] = array(
			'route' => '/',
			'controller' => 'IndexController',
			'action' => 'Cadastro'
		);

		$routes['login'] = array(
			'route' => '/login',
			'controller' => 'IndexController',
			'action' => 'Login'
		);

		$routes['novoUsuario'] = array(
			'route' => '/usuario/salvar',
			'controller' => 'IndexController',
			'action' => 'NovoUsuario'
		);
		
		$this->setRoutes($routes);
	}

}

?>