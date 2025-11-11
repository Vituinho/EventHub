<?php
namespace App;

use PDO;
use PDOException;

class Connection {
    public static function getDb(): PDO {
        $host = 'localhost';
        $dbName = 'crud_eventos';
        $user = 'root';
        $pass = '';

        try {
            // Conecta sem banco, pra criar se não existir
            $conn = new PDO("mysql:host=$host", $user, $pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Cria banco se não existir
            $conn->exec("CREATE DATABASE IF NOT EXISTS $dbName CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

            // Seleciona o banco
            $conn->exec("USE $dbName");

            // Cria a tabela usuarios se não existir
            $conn->exec("CREATE TABLE IF NOT EXISTS usuarios (
                id_usuario INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL,
                telefone VARCHAR(20) NOT NULL,
                tipo VARCHAR(30) NOT NULL default 'USUARIO', 
                senha VARCHAR(255) NOT NULL
            )");

            // Cria a tabela eventos se não existir
            $conn->exec("CREATE TABLE IF NOT EXISTS eventos (
                id_evento INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(100) NOT NULL,
                data DATETIME NOT NULL,
                local VARCHAR(300) NOT NULL,
                detalhes VARCHAR(1000) NOT NULL,
                imagem VARCHAR(255) DEFAULT NULL,
                id_usuario int NOT NULL,
                FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
            )");

            // Cria a tabela user_f2a se não existir
            $conn->exec("CREATE TABLE IF NOT EXISTS user_2fa (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                code VARCHAR(10) NOT NULL,
                expires_at DATETIME NOT NULL,
                used TINYINT(1) DEFAULT 0,
                FOREIGN KEY (user_id) REFERENCES usuarios(id_usuario)
            );");

            return $conn;

        } catch (PDOException $e) {
            echo "Erro ao conectar/criar banco: " . $e->getMessage();
            exit;
        }
    }
}
