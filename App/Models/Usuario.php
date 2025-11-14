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
        $query = "UPDATE usuarios SET nome = :nome, email = :email, telefone = :telefone, senha = :senha, tipo = :tipo WHERE id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':telefone', $this->__get('telefone'));
        $stmt->bindValue(':senha', $this->__get('senha'));
        $stmt->bindValue(':tipo', $this->__get('tipo'));
        $stmt->execute();
    }

    public function verificarEmail($email) {
        $query = "SELECT id_usuario FROM usuarios WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $email, \PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function calcularForcaSenha($senha) {
        $forca = 0;
        if (mb_strlen($senha) >= 8) $forca++;
        if (preg_match('/[a-z]/', $senha)) $forca++;
        if (preg_match('/[A-Z]/', $senha)) $forca++;
        if (preg_match('/\d/', $senha)) $forca++;
        if (preg_match('/[^\p{L}\p{N}]/u', $senha)) $forca++;
        return $forca;
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

        return $this;
    }

    public function autenticar() {
        $query = "SELECT id_usuario, nome, email, telefone, senha, tipo FROM usuarios WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->execute();

        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($usuario && password_verify($this->__get('senha'), $usuario['senha'])) {
            $this->__set('id_usuario', $usuario['id_usuario']);
            $this->__set('nome', $usuario['nome']);
            $this->__set('email', $usuario['email']);
            $this->__set('telefone', $usuario['telefone']);
            $this->__set('tipo', $usuario['tipo']);
            return $this; // retorna o usuário autenticado
        } else {
            return false;
        }
    }
    
    public function tipoUsuario() {
        $query = "SELECT tipo FROM usuarios WHERE id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->execute();

        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($usuario && !empty($usuario['tipo'])) {
            $_SESSION['tipo'] = $usuario['tipo']; // agora o valor é real
            return $usuario['tipo'] === 'ADMIN';
        }

        return false;
    }

    public function DeletarUsuario($id_usuario) {
        // 1. Deleta primeiro os registros dependentes (tabela user_2fa)
        $query2fa = "DELETE FROM user_2fa WHERE user_id = :id_usuario";
        $stmt2fa = $this->db->prepare($query2fa);
        $stmt2fa->bindValue(':id_usuario', $id_usuario);
        $stmt2fa->execute();

        // 2. Agora apaga o usuário
        $query = "DELETE FROM usuarios WHERE id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $id_usuario);
        $stmt->execute();
    }


    public function ConfigurarCascade() {

        // 1 — Remover FK antiga
        $query1 = "ALTER TABLE eventos DROP FOREIGN KEY eventos_ibfk_1;";
        $this->db->exec($query1);

        // 2 — Criar FK nova com CASCADE
        $query2 = "
            ALTER TABLE eventos
            ADD CONSTRAINT eventos_ibfk_1
            FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
            ON DELETE CASCADE
            ON UPDATE CASCADE;
        ";
        $this->db->exec($query2);
    }



}

?>