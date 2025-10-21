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


		
		$this->setRoutes($routes);
	}

}

?>