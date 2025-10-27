<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() {

		/*Parte de cadastro*/

		$routes['cadastro'] = array(
			'route' => '/cadastro',
			'controller' => 'LoginController',
			'action' => 'Cadastro'
		);

		$routes['verificar_email'] = array(
			'route' => '/cadastro/verificar',
			'controller' => 'LoginController',
			'action' => 'verificarEmail'
		);

		/*Parte de login*/

		$routes['login'] = array(
			'route' => '/',
			'controller' => 'LoginController',
			'action' => 'Login'
		);

		$routes['novoUsuario'] = array(
			'route' => '/usuario/salvar',
			'controller' => 'LoginController',
			'action' => 'NovoUsuario'
		);

		$routes['autenticar'] = array(
			'route' => '/login/autenticar',
			'controller' => 'LoginController',
			'action' => 'autenticar'
		);

		/*Parte da home*/

		$routes['home'] = array(
			'route' => '/home',
			'controller' => 'LoginController',
			'action' => 'Home'
		);

		$routes['sobre'] = array(
			'route' => '/eventos/sobre',
			'controller' => 'LoginController',
			'action' => 'Sobre'
		);

		/*Parte de eventos*/

		$routes['cadastro_eventos'] = array(
			'route' => '/eventos/cadastro',
			'controller' => 'EventosController',
			'action' => 'CadastroEventos'
		);

		$routes['cadastrar_eventos'] = array(
			'route' => '/eventos/cadastrar',
			'controller' => 'EventosController',
			'action' => 'CadastrarEventos'
		);

		$routes['detalhes_eventos'] = array(
			'route' => '/eventos/detalhes/',
			'controller' => 'EventosController',
			'action' => 'MostrarDetalhes'
		);

		$this->setRoutes($routes);
	}

}

?>