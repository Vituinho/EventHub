<?php

namespace App\Models;

use MF\Model\Model;

class Usuario extends Model {

    private $id;
    private $nome;
    private $email;
    private $senha;

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }
        
    public function getAll() {
        $query = "SELECT * FROM usuarios";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function atualizar() {
        $query = "UPDATE usuarios SET nome = :nome, email = :email, senha = :senha WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':senha', $this->__get('senha'));
        $stmt->execute();
    }

    public function excluir($id) {
        $query = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    public function salvar() {
    $query = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':nome', $this->__get('nome'));
    $stmt->bindValue(':email', $this->__get('email'));
    $stmt->bindValue(':senha', $this->__get('senha'));
    $stmt->execute();

    return $this;
}


}

?>