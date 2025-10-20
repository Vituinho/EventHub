<?php

namespace MF\Controller;

abstract class Action {

	protected $view;

	public function __construct() {
		$this->view = new \stdClass();
	}

	protected function render($view) {
        $this->view->page = $view;
        require_once "../App/Views/" . $this->view->page . ".phtml";
    }

}

?>