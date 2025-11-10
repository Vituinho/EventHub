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
		$this->render('login');
	}

	public function Home() {
		$home = Container::getModel('Eventos');
		$this->view->eventos = $home->getAll();
		$this->render('home');
	}

	public function NovoUsuario() {
		$usuario = Container::getModel('Usuario');

		$nome = trim($_POST['nome'] ?? '');
		$email = trim($_POST['email'] ?? '');
		$telefone = trim($_POST['telefone'] ?? '');
		$senha = trim($_POST['senha'] ?? '');

		// validações

		if (empty($telefone) || empty($nome) || empty($email) || empty($senha)) {
			header('Location: /cadastro?erro=2'); // valores vazios
			exit;
		}

		if ($usuario->verificarEmail($email)) {
			header('Location: /cadastro?erro=0'); // email já cadastrado
			exit;
		}

		$forca = $usuario->calcularForcaSenha($senha);
		if ($forca < 3) {
			header('Location: /cadastro?erro=3'); // senha fraca
			exit;
		}

		

		// tudo certo, salvar
		$usuario->__set('nome', $nome);
		$usuario->__set('email', $email);
		$usuario->__set('telefone', $telefone);
		$usuario->__set('senha', $senha);
		$usuario->salvar();

		// redireciona pra login
		header('Location: /');
		exit;

	}

	public function PainelAdmin() {
		require_once __DIR__ . '/../Auth.php';
		requireTipo();

		$painelAdmin = Container::getModel('Usuario');
		$eventos = Container::getModel('Eventos');
		$painelAdmin->__set('id_usuario', $_SESSION['id_usuario']);

		$this->view->usuario = $painelAdmin->getAll();
		$this->view->eventos = $eventos->getTudoMesmo();

		$this->render('painel_admin');
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
			$_SESSION['tipo'] = $usuario_autenticado->__get('tipo');

            header('Location: /home');
        } else {
            header('Location: /?erro=1');
        }

    }

	public function EditarUsuario() {
		
		session_start();

        $usuarioModel = Container::getModel('Usuario');

        if (isset($_POST['id_usuario']) && !isset($_POST['email'])) {
            $id_usuario = $_POST['id_usuario'];
            $usuario = $usuarioModel->getById($id_usuario);

			
            if ($usuario['id_usuario'] != $id_usuario && $_SESSION['tipo'] != 'ADMIN') {
                header('Location: /home');
                exit;
            }

            $this->view->usuario = $usuario;
            $this->render('editar_usuario');
            return;
        }

		

        // Se veio formulário de edição completo, atualiza
        if (isset($_POST['id_usuario'], $_POST['nome'], $_POST['email'], $_POST['telefone'], $_POST['tipo'])) {
            $id_usuario = $_POST['id_usuario'];

            $usuarioAtual = $usuarioModel->getById($id_usuario);
            if ($usuarioAtual['id_usuario'] != $id_usuario && $_SESSION['tipo'] != 'ADMIN') {
                header('Location: /home');
                exit;
            }

			if ($_SESSION['tipo'] === 'ADMIN') {
				$usuarioModel->__set('nome', $_POST['nome']);
				$usuarioModel->__set('email', $_POST['email']);
				$usuarioModel->__set('telefone', $_POST['telefone']);
				
				if (!empty($_POST['senha'])) {
					$novaSenha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
					$usuarioModel->__set('senha', $novaSenha);
				} else {
					// mantém hash antiga
					$usuarioModel->__set('senha', $usuarioAtual['senha']);
				}

				$usuarioModel->__set('tipo', $_POST['tipo']);
				$usuarioModel->__set('id_usuario', $usuarioAtual['id_usuario']);

				$usuarioModel->atualizar();

				header('Location: /admin/painel');
				exit;
			} else {
				$usuarioModel->__set('nome', $_POST['nome']);
				$usuarioModel->__set('email', $_POST['email']);
				$usuarioModel->__set('telefone', $_POST['telefone']);
				
				if (!empty($_POST['senha'])) {
					$novaSenha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
					$usuarioModel->__set('senha', $novaSenha);
				} else {
					// mantém hash antiga
					$usuarioModel->__set('senha', $usuarioAtual['senha']);
				}


				$usuarioModel->__set('id_usuario', $_SESSION['id_usuario']);

				$usuarioModel->atualizar();

				header('Location: /home');
				exit;
			}

        }

		// Se nada vier, redireciona
		header('Location: /home');
		exit;
	}

	public function ExcluirUsuario() {

		session_start();

        $usuarioModel = Container::getModel('Usuario');
		$usuarioModel->ConfigurarCascade();

			// Se só veio o id_evento via POST, mostra formulário
			if (isset($_POST['id_usuario']) && !isset($_POST['nome'])) {

				$id_usuario = $_POST['id_usuario'];
				$usuario = $usuarioModel->getById($id_usuario);

				if ($usuario['id_usuario'] != $_SESSION['id_usuario'] && $_SESSION['tipo'] != 'ADMIN') {
					header('Location: /home');
					exit;
				}

				$this->view->usuario = $usuario;
				$usuarioModel->DeletarUsuario($id_usuario);

				if ($_SESSION['tipo'] === 'ADMIN') {
					header('Location: /admin/painel');
					return;
				} else {
					header('Location: /home');
					return;
				}
			}
		// Se nada vier, redireciona
		header('Location: /home');
		exit;
	}

	public function PerfilUsuario() {
		session_start();
		$usuario = Container::getModel('Usuario');
		$id_usuario = $_SESSION['id_usuario'];

		$this->view->usuario = $usuario->getById($id_usuario);
		$this->render('perfil_usuario');
	}

	public function sobre() {
		$this->render('sobre');
	}

	public function Logout() {
        session_start();
        session_destroy();
        header('Location: /');
    }
}


?>