<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

session_start();

class EventosController extends Action {

    public function CadastroEventos() {
		$cadastro_eventos = Container::getModel('Eventos');
		$this->view->eventos = $cadastro_eventos->getAll();
		$this->render('cadastro_eventos');
	}

	public function CadastrarEventos() {

		$cadastro_eventos = Container::getModel('Eventos');

		// Setando valores
		$cadastro_eventos->__set('nome', $_POST['nome'] ?? '');
		$cadastro_eventos->__set('data', $_POST['data'] ?? '');
		$cadastro_eventos->__set('local', $_POST['local'] ?? '');
		$cadastro_eventos->__set('detalhes', $_POST['detalhes'] ?? '');
		$cadastro_eventos->__set('imagem', $caminhoRelativo ?? null);
		$cadastro_eventos->__set('id_usuario', $_SESSION['id_usuario']);
		
		$this->view->eventos = $cadastro_eventos->salvar();

		$this->view->eventos = $cadastro_eventos->getAll();
		$this->render('cadastro_eventos');

		header('Location: /home');
		exit;
	}

	public function MostrarDetalhes() {
		$id_evento = $_POST['id_evento'] ?? null; // antes era $_GET
		$cadastro_eventos = Container::getModel('Eventos');

		if ($id_evento) {
			$evento = $cadastro_eventos->getById($id_evento);

			$this->view->evento = $evento;
		} else {
			$this->view->evento = null;
		}

		$this->render('detalhes_eventos');
	}

	public function MeusEventos() {
		$id_usuario = $_SESSION['id_usuario'];
		$cadastro_eventos = Container::getModel('Eventos');

		// Busca todos os eventos do usuário
		$this->view->eventos = $cadastro_eventos->getByIDUsuario($id_usuario);

		// Garante que sempre seja array
		if (!is_array($this->view->eventos)) {
			$this->view->eventos = [];
		}

		$this->render('meus_eventos');
	}

	public function EditarEventos() {
        $eventosModel = Container::getModel('Eventos');

        // Se só veio o id_evento via POST, mostra formulário
        if (isset($_POST['id_evento']) && !isset($_POST['nome'])) {
            $id_evento = $_POST['id_evento'];
            $evento = $eventosModel->getById($id_evento);

            if ($evento['id_usuario'] != $_SESSION['id_usuario']) {
                header('Location: /eventos');
                exit;
            }

            $this->view->evento = $evento;
            $this->render('editar_eventos');
            return;
        }

        // Se veio formulário de edição completo, atualiza
        if (isset($_POST['id_evento'], $_POST['nome'], $_POST['data'], $_POST['local'], $_POST['detalhes'])) {
            $id_evento = $_POST['id_evento'];

            $eventoAtual = $eventosModel->getById($id_evento);
            if ($eventoAtual['id_usuario'] != $_SESSION['id_usuario']) {
                header('Location: /eventos');
                exit;
            }

            $eventosModel->__set('id_evento', $id_evento);
            $eventosModel->__set('nome', $_POST['nome']);
            $eventosModel->__set('data', $_POST['data']);
            $eventosModel->__set('local', $_POST['local']);
            $eventosModel->__set('detalhes', $_POST['detalhes']);
            $eventosModel->__set('id_usuario', $_SESSION['id_usuario']);
            $eventosModel->__set('imagem', $eventoAtual['imagem']); // mantém imagem antiga se não enviar nova

            $eventosModel->atualizar();

            header('Location: /eventos');
            exit;
        }

		// Se nada vier, redireciona
		header('Location: /eventos');
		exit;
	}


}


?>