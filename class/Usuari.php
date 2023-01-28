<?php
require_once(__DIR__ . '/Response.php');
require_once(__DIR__ . '/UsuariSingleInstance.php');
require_once(__DIR__ . '/Connexio.php');
class Usuari extends Connexio
{
    // Properties
    public $response;
    public $usuaris = array(); // todos los usuarios de la bdd

    // Methods
    function __construct()
    {
        parent::__construct();
        $this->response = new Response();
    }

    public function createUsuari($name, $surnames, $phone, $username, $pwd)
    {
        if (!($this->checkUser($username, $pwd))->correctOperation) {
            try {
                $insert = "INSERT INTO usuaris (nom, llinatges, telefon, username, password) VALUES (?, ?, ?, ?, SHA2(?,256))";
                $stmt = $this->preparedInsert($insert);
                $stmt->bind_param("sssss", $name, $surnames,  $phone, $username, $pwd);
                $stmt->execute();
                $this->response->setCorrectOperation(true);
            } catch (Exception $e) {
                $this->response->setCorrectOperation(false);
            } finally {
                $stmt->close();
            }
        } else {
            $this->response->setCorrectOperation(false);
        }
        return $this->response;
    }

    public function updateUsuari($id, $nom, $llinatges, $telefon, $username, $pwd)
    {
        // Asegurarnos que no hay ningun dato vacio
        if ($id != '' && $nom != '' && $llinatges != '' && $telefon != '' && $username != '' &&  $pwd != '') {
            $query_update = "UPDATE usuaris SET nom='$nom', llinatges='$llinatges', telefon='$telefon', username='$telefon', password=SHA2('$pwd',256) WHERE idusuari = $id";
            try {
                $this->query($query_update);
                $this->response->setCorrectOperation(true);
            } catch (Exception $ex) {
                $this->response->setCorrectOperation(false);
            }
        } else {
            $this->response->setCorrectOperation(false);
        }
        return $this->response;
    }

    public function deleteUsuari($id)
    {
        $reservaUtil = new Reserva();
        // Si no tiene reservas
        if (count($reservaUtil->reservesPerUsuari($id)->data) <= 0) {
            $consulta = "delete from usuaris where idusuari='$id'";
            try {
                $this->query($consulta);
                $this->response->setCorrectOperation(true);
            } catch (Exception $ex) {
                $this->response->setCorrectOperation(false);
            }
        } else {
            $this->response->setCorrectOperation(false);
        }
        return $this->response;
    }

    public function getUsuari($username, $password)
    {
        $consulta = "select * from usuaris where username='$username' and password=SHA2('$password',256)";
        $result = $this->query($consulta);
        $usuari = null;
        if ($result->num_rows > 0) {
            while ($rowData = $result->fetch_assoc()) {
                $usuari = new UsuariSingleInstance($rowData["idusuari"], $rowData["nom"], $rowData["llinatges"], $rowData["telefon"], $rowData["username"], $rowData["password"]);
            }
            $this->response->setCorrectOperation(true);
        } else {
            $this->response->setCorrectOperation(false);
        }
        $this->response->setData($usuari);
        return $this->response;
    }

    public function getUsuaris()
    {
        $sql = "SELECT idusuari, nom, llinatges, telefon, username, password FROM usuaris";
        $result = $this->query($sql);
        $all_usuaris = array();
        if ($result->num_rows > 0) {
            while ($fila = $result->fetch_assoc()) {
                array_push($all_usuaris, new UsuariSingleInstance($fila["idusuari"], $fila["nom"], $fila["llinatges"], $fila["telefon"], $fila["username"], $fila["password"]));
            }
            $this->response->setCorrectOperation(true);
        } else {
            $this->response->setCorrectOperation(false);
        }
        $result->free();
        $this->usuaris = $all_usuaris;

        $this->response->setData($all_usuaris);
        return $this->response;
    }

    public function checkUser($username, $password)
    {
        $query = "select count(*) as total from usuaris where username='$username' and password=SHA2('$password',256)";
        $data = $this->query($query)->fetch_assoc();
        if ($data['total'] > 0) {
            $this->response->setCorrectOperation(true);
        } else {
            $this->response->setCorrectOperation(false);
        }
        return $this->response;
    }
}
