<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() {


		$routes['cadastro'] = array(
			'route' => '/cadastro',
			'controller' => 'indexController',
			'action' => 'Cadastro'
		);
		
		$this->setRoutes($routes);
	}

}

?>