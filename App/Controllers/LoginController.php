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

		$nome = trim($_POST['nome'] ?? '');
		$email = trim($_POST['email'] ?? '');
		$telefone = trim($_POST['telefone'] ?? '');
		$senha = $_POST['senha'] ?? '';

		if(empty($email)) {
			header('Location: /cadastro?erro=2'); // email vazio
			exit;
		}

		if($usuario->verificarEmail($email)) {
			header('Location: /cadastro?erro=0'); // email já cadastrado
			exit;
		}

		if(strlen($senha) < 8) {
			header('Location: /cadastro?erro=1'); // senha curta
			exit;
		}

		// tudo certo, salvar
		$usuario->__set('nome', $nome);
		$usuario->__set('email', $email);
		$usuario->__set('telefone', $telefone);
		$usuario->__set('senha', $senha);

		$usuario->salvar();

		// redireciona para login
		header('Location: /home');
		exit;
	}

	public function verificarEmail() {
		$usuario = Container::getModel('Usuario');

		$email = trim($_POST['email'] ?? '');
		if(empty($email)) {
			header('Location: /cadastro?erro=2'); // email vazio
			exit;
		}
		if($usuario->verificarEmail($email)) {
			header('Location: /cadastro?erro=0'); // email já cadastrado
			exit;
		} else {
			// email não existe, segue para salvar
			$usuario->__set('email', $email);
			$usuario->__set('nome', $_POST['nome'] ?? '');
			$usuario->__set('telefone', $_POST['telefone'] ?? '');
			$usuario->__set('senha', $_POST['senha'] ?? '');

			$usuario->salvar();
		}
	}


	public function autenticar() {
		session_start();

		$usuario = Container::getModel('Usuario');
        $usuario->__set('email', $_POST['email']);
        $usuario->__set('senha', $_POST['senha']);

        $usuario_autenticado = $usuario->autenticar();

        if ($usuario_autenticado && !empty($usuario_autenticado->__get('id_usuario'))) {
            $_SESSION['id_usuario'] = $usuario_autenticado->__get('id_usuario');
            $_SESSION['nome'] = $usuario_autenticado->__get('nome');
            $_SESSION['email'] = $usuario_autenticado->__get('email');
			$_SESSION['telefone'] = $usuario_autenticado->__get('telefone');

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