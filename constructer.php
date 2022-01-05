<?php
include_once "db.php";
class Crud{

    private $conn;
    public function __construct(){
        $this->conn = getdbconnection();
       
    }
    public function create($data,$table){

        $col=implode(',',array_keys($data));
        $place_holder=':'.implode(',:',array_keys($data));
        $sql="INSERT INTO $table($col) VALUES($place_holder)";
        $stmt=$this->conn->prepare($sql);
        $stmt->execute($data);
        return $this->conn->lastInsertId();

    }
    public function read($sql_query) {
        $stmt=$this->conn->prepare($sql_query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function update($sql_query){
        $stmt=$this->conn->prepare($sql_query);
        $stmt->execute();
    }
    public function delete($sql_query){
        $stmt=$this->conn->prepare($sql_query);
        $stmt->execute();
    }
}
?>