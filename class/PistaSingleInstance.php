<?php
class PistaSingleInstance {
    public $id;
    public $type;
    public $price;

    function __construct($id,$type,$price)
    {
        $this->id = $id;
        $this->type = $type;
        $this->price = $price;
    }
}
