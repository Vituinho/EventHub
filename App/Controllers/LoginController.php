<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class LoginController extends Action {

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
		$home = Container::getModel('Eventos');
		$this->view->eventos = $home->getAll();
		$this->render('Home');
	}

	public function NovoUsuario() {
		$usuario = Container::getModel('Usuario');

		$usuario->__set('nome', $_POST['nome']);
		$usuario->__set('email', $_POST['email']);
		$usuario->__set('senha', $_POST['senha']);

		$senha = $_POST['senha'];

		if(strlen($senha) < 8) {
			header('Location: /cadastro?erro=1');
			exit;
		} else {
			$usuario->salvar();
			$this->render('Login');
		}

	}

	public function verificarEmail() {
		$usuario = Container::getModel('Usuario');

		$usuario->__set('email', $_POST['email']);

		$email = $_POST['email'];

		$query = "SELECT id_usuario FROM usuarios WHERE email = :email";

		$stmt = $usuario->db->prepare($query);
		$stmt->bindValue(':email', $email);
		$stmt->execute();

		if($stmt->rowCount()>0) {
			header('Location: /cadastro?erro=0');
			exit;
		}

		header('Location: /usuario/salvar');
		exit;
	}

	public function autenticar() {
		session_start();

		$usuario = Container::getModel('Usuario');
        $usuario->__set('email', $_POST['email']);
        $usuario->__set('senha', $_POST['senha']);

        $usuario_autenticado = $usuario->autenticar();

        if ($usuario_autenticado && $usuario_autenticado->__get('id_usuario') != '') {
            $_SESSION['id_usuario'] = $usuario_autenticado->__get('id_usuario');
            $_SESSION['nome'] = $usuario_autenticado->__get('nome');
            $_SESSION['email'] = $usuario_autenticado->__get('email');

            header('Location: /home');
        } else {
            header('Location: /?erro=1');
        }
    }

	public function logout() {
        session_start();
        session_destroy();
        header('Location: /');
    }
}


?>