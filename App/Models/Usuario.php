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
        $query = "SELECT * FROM usuarios WHERE id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $id);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function atualizar() {
        $query = "UPDATE usuarios SET nome = :nome, email = :email, telefone = :telefone, senha = :senha WHERE id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':telefone', $this->__get('telefone'));
        $stmt->bindValue(':senha', $this->__get('senha'));
        $stmt->execute();
    }

    public function excluir($id) {
        $query = "DELETE FROM usuarios WHERE id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $id);
        $stmt->execute();
    }

    public function salvar() {
        $query = "INSERT INTO usuarios (nome, email, telefone, senha) VALUES (:nome, :email, :telefone, :senha)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':telefone', $this->__get('telefone'));
        $stmt->bindValue(':senha', password_hash($this->__get('senha'), PASSWORD_DEFAULT));
        $stmt->execute();

        header('Location: /home');
        exit;

        return $this;
    }

    public function autenticar() {
        $query = "SELECT id_usuario, nome, email, telefone, senha FROM usuarios WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->execute();

        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($usuario && password_verify($this->__get('senha'), $usuario['senha'])) {
            $this->__set('id_usuario', $usuario['id_usuario']);
            $this->__set('nome', $usuario['nome']);
            $this->__set('email', $usuario['email']);
            $this->__set('telefone', $usuario['telefone']);
            return $this; // retorna o usuário autenticado
        } else {
            return false;
        }
    }


}

?>