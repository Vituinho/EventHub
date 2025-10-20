<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function Cadastro() {
		$cadastro = Container::getModel('Usuario');
		$this->view->usuarios = $cadastro->getAll();
		$this->render('Cadastro');
	}

	public function Login() {
		$cadastro = Container::getModel('Usuario');
		$this->view->usuarios = $cadastro->getAll();
		$this->render('Login');
	}

	public function NovoUsuario() {
		$this->render('novo_usuario');
	}
}


?>