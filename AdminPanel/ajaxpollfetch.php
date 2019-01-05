<?php
include "../logic/connection.php";
header("Content-type:application/json");
if(isset($_POST['btn'])){
    $id = $_POST['id'];
    $query = "SELECT * FROM poll p INNER JOIN options o ON o.poll_id = p.poll_id WHERE p.poll_id = :id";
    $prepared = $connection->prepare($query);
    $prepared->bindParam(":id",$id);
    $prepared->execute();
    $resultquery = $prepared->fetchAll();
    echo json_encode(['userdata'=>$resultquery]);
}