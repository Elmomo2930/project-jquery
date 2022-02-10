<?php

class Database
{
    private $server = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $dbName = 'mydata';
    protected $conn;
     
     
    public function __construct()
    {
        try {
            $dn = "mysql:host={$this->server}; dbname={$this->dbName}; charset=utf8";
            $options = array(PDO::ATTR_PERSISTENT);
            $this->conn = new PDO($dn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            echo "¡Error falla conexion!: " . $e->getMessage();
        }
    }
}

?>
