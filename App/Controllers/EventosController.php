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
		$cadastro_eventos->__set('id_usuario', $_SESSION['id_usuario']); 

		$this->view->eventos = $cadastro_eventos->salvar();

		$this->view->eventos = $cadastro_eventos->getAll();
		$this->render('cadastro_eventos');

		header('Location: /home');
		exit;
	}

}


?>