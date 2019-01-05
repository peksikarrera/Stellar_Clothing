<?php
include "connection.php";
if(isset($_POST['colorselected'])){
    $colorID = $_POST['colorID'];
    $sizeID = $_POST['sizeID'];
    $productID = $_POST['productID'];
    $query = "SELECT SUM(quantity) as quantity FROM ((products_sizes_colors psc INNER JOIN products p ON psc.product_id = p.product_id) INNER JOIN sizes s ON s.size_id = psc.size_id) INNER JOIN colors c ON c.color_id = psc.color_id WHERE p.product_id = :productID AND s.size_id = :sizeID AND c.color_id = :colorID GROUP BY c.color_id, p.product_id, s.size_id";
    $preparedquery = $connection->prepare($query);
    $preparedquery->bindParam(":sizeID",$sizeID);
    $preparedquery->bindParam(":colorID",$colorID);
    $preparedquery->bindParam(":productID",$productID);
    try{
        $result = $preparedquery->execute();
        if($result){
            $result = $preparedquery->fetch();
            echo json_encode($result);
        }
        else{
            http_response_code(422);
        }
    }
    catch(PDOException $e){
        http_response_code(500);
    }
}