<?php
include "connection.php";
if(isset($_POST['sizeselected'])){
    $sizeID = $_POST['sizeID'];
    $productID = $_POST['productID'];
    $querycolors = "SELECT c.color_id, c.color as color FROM ((((productphotos pp INNER JOIN products p ON p.product_id = pp.product_id) INNER JOIN prices pr ON p.product_id = pr.product_id) INNER JOIN products_sizes_colors psc ON psc.product_id = p.product_id) INNER JOIN sizes s ON s.size_id = psc.size_id) INNER JOIN colors c ON c.color_id = psc.color_id WHERE p.product_id = :productID AND s.size_id = :sizeID GROUP BY c.color_id";
    $preparedquery = $connection->prepare($querycolors);
    $preparedquery->bindParam(":productID",$productID);
    $preparedquery->bindParam(":sizeID",$sizeID);
    try{
        $result = $preparedquery->execute();
    }
    catch(PDOException $e){
        http_response_code(500);
    }
    if($result){
        $result = $preparedquery->fetchAll();
        echo json_encode($result);
    }
    else{
        http_response_code(422);
    }
}