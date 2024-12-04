<?php
include('connection.php');

class Employee {
    private $noEmpleado;
    private $password;

    public function setNoEmpleado($noEmpleado) {
        $this->noEmpleado = $noEmpleado;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getUserData() {
        $connection = connection();
        $numE = $this->noEmpleado;
        $password = $this->password;
    
        $query = "CALL inicioSesion(?, ?, @tipo, @nom)";
        $stmt = $connection->prepare($query);
        
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $connection->error);
        }
    
        $stmt->bind_param("si", $password, $numE);
        $stmt->execute();
        $stmt->close();
    
        $result = $connection->query('SELECT @nom AS nombreCompleto, @tipo AS tipoEmpleado');
    
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            var_dump($row);
            return $row;
        } else {
            echo "Error: No se obtuvieron resultados.";
            return false;
        }
    }      
}
?>
