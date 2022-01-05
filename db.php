<?php
function getdbconnection(){
    $servername="localhost";
    $username="root";
    $password="";
    $db="afee";
    try{
        $conn=new PDO("mysql:host=$servername;dbname=$db",$username,$password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
        return $conn;
        }catch(PDOException $e){echo "connection failed:" . $e->getMessage();

        }
}
?>