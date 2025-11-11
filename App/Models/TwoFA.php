<?php

namespace App\Models;

use MF\Model\Model;

class TwoFA extends Model {

    public function salvarCodigo($user_id, $code, $expires_at)
    {
        $query = "INSERT INTO user_2fa (user_id, code, expires_at, used) 
                  VALUES (?, ?, ?, 0)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$user_id, $code, $expires_at]);
    }

    public function validarCodigo($user_id, $code)
    {
        $query = "SELECT * FROM user_2fa 
                  WHERE user_id = ? AND code = ? AND used = 0 
                  AND expires_at > NOW()
                  ORDER BY id DESC
                  LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$user_id, $code]);
        return $stmt->fetch();
    }

    public function marcarUsado($id)
    {
        $query = "UPDATE user_2fa SET used = 1 WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
    }
}

?>
