<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class EventosController extends Action {

    public function CadastroEventos() {
		$cadastro_eventos = Container::getModel('Usuario');
		$this->view->usuarios = $cadastro_eventos->getAll();
		$this->render('cadastro_eventos');
	}

}


?>