<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class EventosController extends Action {

    public function CadastroEventos() {
		$cadastro_eventos = Container::getModel('Eventos');
		$this->view->eventos = $cadastro_eventos->salvar();
		$this->render('cadastro_eventos');
	}

}


?>