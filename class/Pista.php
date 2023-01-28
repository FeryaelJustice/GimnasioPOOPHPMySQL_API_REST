<?php
require_once(__DIR__ . '/Response.php');
require_once(__DIR__ . '/PistaSingleInstance.php');
require_once(__DIR__ . '/Connexio.php');
class Pista extends Connexio
{

    public $response;

    function __construct()
    {
        parent::__construct();
        $this->response = new Response();
    }

    function getPistes()
    {
        // Retorna un array con estructura array[id]=nomPista
        $sql = "select * from pistes";
        $result = $this->query($sql);
        $pistes = array();
        if ($result->num_rows > 0) {
            while ($fila = $result->fetch_assoc()) {
                array_push($pistes, new PistaSingleInstance($fila["idpista"], $fila["tipo"], $fila["preu"]));
            }
            $this->response->setCorrectOperation(true);
        } else {
            $this->response->setCorrectOperation(false);
        }
        $result->free();
        $this->response->setData($pistes);
        return $this->response;
    }
}
