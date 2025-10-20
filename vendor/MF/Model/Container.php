<?php
namespace MF\Model;

use App\Connection;

class Container {

    public static function getModel($model) {
        $class = "\\App\\Models\\".ucfirst($model);
        $conn = Connection::getDb(); // pega a PDO

        return new $class($conn);
    }
}
