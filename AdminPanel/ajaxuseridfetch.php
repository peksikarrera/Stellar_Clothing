<?php
include "../logic/connection.php";
header("Content-type:application/json");
if(isset($_POST['btn'])){
    $id = $_POST['id'];
    $query = "SELECT * FROM ((users u INNER JOIN roles r ON r.role_id = u.role_id) INNER JOIN usercarddata ucd ON ucd.user_id = u.user_id) INNER JOIN personaluserdata pud ON pud.user_id = u.user_id WHERE u.user_id = :userID";
    $prepared = $connection->prepare($query);
    $prepared->bindParam(":userID",$id);
    $prepared->execute();
    $resultquery = $prepared->fetchAll();
    echo json_encode(['userdata'=>$resultquery]);
}