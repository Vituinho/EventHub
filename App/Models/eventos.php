<?php

namespace App\Models;

use MF\Model\Model;

class Eventos extends Model {

    private $id_evento;
    private $nome;
    private $data;
    private $local;
    private $detalhes;
    private $id_usuario;

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }
        
    public function getAll() {
        $query = "SELECT * FROM eventos";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function atualizar() {
        $query = "
        UPDATE
         eventos SET nome = :nome, data = :data, local = :local, detalhes = :detalhes, id_usuario = :id_usuario 
         WHERE id_evento = :id_evento
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_evento', $this->__get('id_evento'));
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':data', $this->__get('data'));
        $stmt->bindValue(':local', $this->__get('local'));
        $stmt->bindValue(':detalhes', $this->__get('detalhes'));
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->execute();
    }

    public function excluir($id) {
        $query = "DELETE FROM eventos WHERE id_evento = :id_evento";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_evento', $id);
        $stmt->execute();
    }

    public function salvar() {
        $query = "INSERT INTO eventos (nome, data, local, detalhes, id_usuario) VALUES (:nome, :data, :local, :detalhes, :id_usuario)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':data', $this->__get('data'));
        $stmt->bindValue(':local', $this->__get('local'));
        $stmt->bindValue(':detalhes', $this->__get('detalhes'));
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->execute();

    return $this;
    }

}

?>