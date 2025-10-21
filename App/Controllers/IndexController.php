<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function Cadastro() {
		$cadastro = Container::getModel('Usuario');
		$this->view->usuarios = $cadastro->getAll();
		$this->render('cadastro');
	}

	public function Login() {
		$login = Container::getModel('Usuario');
		$this->view->usuarios = $login->getAll();
		$this->render('Login');
	}

	public function Home() {
		$login = Container::getModel('Usuario');
		$this->view->usuarios = $login->getAll();
		$this->render('Home');
	}

	public function NovoUsuario() {
		$cadastro = Container::getModel('Usuario');

		$cadastro->__set('nome', $_POST['nome']);
		$cadastro->__set('email', $_POST['email']);
		$cadastro->__set('senha', $_POST['senha']);

		$cadastro->salvar();
		$this->render('Login');
	}

	public function CadastroEventos() {
		$cadastro_eventos = Container::getModel('Usuario');
		$this->view->usuarios = $cadastro_eventos->getAll();
		$this->render('cadastro_eventos');
	}
}


?>