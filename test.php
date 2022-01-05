<?php
include_once "db.php";
include_once "constructer.php";
$data=[
    
    'mv_title'=>'musical','mv_year_released'=>'2011-03-05'
    

];
$afe=new Crud();
//$afe->create($data,"movies");
//var_dump($afe->read('SELECT * FROM movies'));
//$afe->update("UPDATE movies SET mv_title='title' where mv_id=1");
$afe->delete("DELETE from movies where mv_id=1");
?>