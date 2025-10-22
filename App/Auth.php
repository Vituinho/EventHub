<?php

function requireLogin() {
    session_start();
    if (!isset($_SESSION['id_usuario']) || $_SESSION['id_usuario'] == '') {
        header('Location: /');
        exit;
    }
}

?>