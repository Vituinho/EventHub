<?php

    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    function requireLogin() {
        if (!isset($_SESSION['id_usuario']) || $_SESSION['id_usuario'] == '') {
            header('Location: /');
            exit;
        }
    }

    function requireTipo() {

        if ($_SESSION['tipo'] != 'ADMIN') {
            header('Location: /home?erro=1');
            exit;
        }

        return true;

    }

?>