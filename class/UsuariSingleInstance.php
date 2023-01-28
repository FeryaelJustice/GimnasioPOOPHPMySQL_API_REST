<?php
class UsuariSingleInstance
{
    public $id;
    public $nom;
    public $llinatges;
    public $telefon;
    public $username;
    public $password;

    function __construct($id, $name, $surnames, $phone, $username, $pwd)
    {
        $this->id = $id;
        $this->nom = $name;
        $this->llinatges = $surnames;
        $this->telefon = $phone;
        $this->username = $username;
        $this->password = $pwd;
    }

    public function __toString()
    {
        return "$this->nom $this->llinatges";
    }
}
