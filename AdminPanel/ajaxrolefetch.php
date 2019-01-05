<?php
include "../logic/connection.php";
header("Content-type:application/json");
if(isset($_POST['btn'])){
    $id = $_POST['id'];
    $query = "SELECT * FROM roles WHERE role_id = :roleid";
    $prepared = $connection->prepare($query);
    $prepared->bindParam(":roleid",$id);
    $prepared->execute();
    $resultquery = $prepared->fetchAll();
    echo json_encode(['userdata'=>$resultquery]);
}