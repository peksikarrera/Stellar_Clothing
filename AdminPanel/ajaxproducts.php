<?php
include "../logic/connection.php";
header("Content-type:application/json");
if(isset($_POST['btn'])){
    $id = $_POST['id'];
    $query = "SELECT * FROM products p INNER JOIN categories_subcategories_types cst ON cst.product_id = p.product_id WHERE p.product_id = :id";
    $prepared = $connection->prepare($query);
    $prepared->bindParam(":id",$id);
    $prepared->execute();
    $resultquery = $prepared->fetchAll();
    echo json_encode(['userdata'=>$resultquery]);
}