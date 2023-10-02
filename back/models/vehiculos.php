<?php
class Vehicle
{
    private $conn;
    private $table_name = "vehiculos";

    public $id;
    public $matricula;
    public $marca;
    public $modelo;
    public $color;
    public $lugar;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function create()
    {
    }

    function read()
    {
    }

    function delete()
    {
    }
}
?>
