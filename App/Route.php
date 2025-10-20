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
		
		$this->setRoutes($routes);
	}

}

?>