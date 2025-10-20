<?php

namespace MF\Controller;

abstract class Action {

	protected $view;

	public function __construct() {
		$this->view = new \stdClass();
	}

	protected function render($view, $layout = 'layout') {
    $this->view->page = $view;

    if(file_exists("../App/Views/".$layout.".phtml")) {
        require_once "../App/Views/".$layout.".phtml";
    } else {
        $this->content();
    }
}

protected function content() {
    // Aqui, pega direto da pasta Views
    require_once "../App/Views/".$this->view->page.".phtml";
}

}

?>