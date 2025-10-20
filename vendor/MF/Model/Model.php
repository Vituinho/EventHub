<?php
namespace MF\Model;

use PDO;

class Model {

    protected PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }
}
