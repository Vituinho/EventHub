<?php

namespace App\Models;

use MF\Model\Model;

class Eventos extends Model {

    private $id_evento;
    private $nome;
    private $data;
    private $local;
    private $detalhes;
    private $imagem;
    private $id_usuario;

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    public function getTudoMesmo() {
        $query = "SELECT * FROM eventos ORDER BY data ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
        
    public function getAll() {
        $query = "SELECT * FROM eventos WHERE data >= CURDATE() ORDER BY data ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllAntigos() {
        $query = "SELECT * FROM eventos WHERE data < CURDATE() ORDER BY data DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id_evento) {
        /* Antes do INNER JOIN
        $query = "SELECT * FROM eventos WHERE id_evento = :id_evento";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_evento', $id_evento);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
        */

        $query = "
            SELECT 
                e.*, 
                u.nome AS nome_usuario, 
                u.email AS email_usuario, 
                u.telefone AS telefone_usuario
            FROM eventos e
            INNER JOIN usuarios u ON e.id_usuario = u.id_usuario
            WHERE e.id_evento = :id_evento
            LIMIT 1
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_evento', $id_evento, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getByIdUsuario($id_usuario) {
        
        $query = "SELECT * FROM eventos WHERE id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $id_usuario);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function atualizar() {
        $imagem = $this->__get('imagem');

        $uploadDir = 'uploads/';
        if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        // Se enviou nova imagem
        if (!empty($_FILES['imagem']['name'])) {
            $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $nomeArquivo = uniqid('evento_', true) . '.' . $ext;
            $caminhoRelativo = $uploadDir . $nomeArquivo;

            if(move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoRelativo)) {
                $imagem = $caminhoRelativo;
            }
        }

        $query = "
            UPDATE eventos
            SET nome = :nome, data = :data, local = :local, detalhes = :detalhes" 
            . ($imagem ? ", imagem = :imagem" : "") . "
            WHERE id_evento = :id_evento
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_evento', $this->__get('id_evento'), \PDO::PARAM_INT);
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':data', $this->__get('data'));
        $stmt->bindValue(':local', $this->__get('local'));
        $stmt->bindValue(':detalhes', $this->__get('detalhes'));

        if ($imagem) {
            $stmt->bindValue(':imagem', $imagem);
        }

        $stmt->execute();
    }


    public function DeletarEventos($id_evento) {
        $query = "DELETE FROM eventos WHERE id_evento = :id_evento";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_evento', $id_evento);
        $stmt->execute();
    }

    public function salvar() {

        $uploadDir = 'uploads/';
        if(!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imagem = null;

        if (!empty($_FILES['imagem']['name'])) {
            $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $nomeArquivo = uniqid('evento_', true) . '.' . $extensao;
            $caminhoRelativo = $uploadDir . $nomeArquivo;

            if(move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoRelativo)) {
                $imagem = $caminhoRelativo;
            }
        }

        $this->__set('imagem', $imagem);

        $query = "INSERT INTO eventos (nome, data, local, detalhes, imagem, id_usuario) VALUES (:nome, :data, :local, :detalhes, :imagem, :id_usuario)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':data', $this->__get('data'));
        $stmt->bindValue(':local', $this->__get('local'));
        $stmt->bindValue(':detalhes', $this->__get('detalhes'));
        $stmt->bindValue(':imagem', $this->__get('imagem'));
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));

        $stmt->execute();
    }

}

?>