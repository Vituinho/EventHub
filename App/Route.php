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

		$routes['verificacoes'] = array(
			'route' => '/cadastro/verificar',
			'controller' => 'LoginController',
			'action' => 'verificacoes'
		);

		$routes['painel_admin'] = array(
			'route' => '/admin/painel',
			'controller' => 'LoginController',
			'action' => 'PainelAdmin'
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

		$routes['meus_eventos'] = array(
			'route' => '/eventos',
			'controller' => 'EventosController',
			'action' => 'MeusEventos'
		);

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
		
		$routes['editar_eventos'] = array(
			'route' => '/eventos/editar',
			'controller' => 'EventosController',
			'action' => 'EditarEventos'
		);

		$routes['deletar_eventos'] = array(
			'route' => '/eventos/deletar',
			'controller' => 'EventosController',
			'action' => 'DeletarEventos'
		);

		$routes['detalhes_eventos'] = array(
			'route' => '/eventos/detalhes/',
			'controller' => 'EventosController',
			'action' => 'MostrarDetalhes'
		);

		$routes['antigos_eventos'] = array(
			'route' => '/eventos/antigos',
			'controller' => 'EventosController',
			'action' => 'AntigosEventos'
		);

		$this->setRoutes($routes);
	}

}

?>