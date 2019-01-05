<?php
session_start();
include "connection.php";
if(isset($_SESSION['user'])){
    if($_SESSION['user']->rolename == 'normal_user'){
        if(isset($_POST['rated'])){
            $rate = $_POST['rate'];
            $productID = $_POST['productID'];
            $time = date("Y-m-d H:i:s");
            $userID = (int)$_SESSION['user']->user_id;
            $checkquery = "SELECT * FROM users_ratings WHERE user_id = :userID AND product_id = :productID";
            $preparedquery = $connection->prepare($checkquery);
            $preparedquery->bindParam(":userID",$userID);
            $preparedquery->bindParam(":productID",$productID);
            try{
                $result = $preparedquery->execute();
            }
            catch(PDOException $e){
                http_response_code(500);
            }
            if($result){
                if($preparedquery->rowCount() > 0){
                    http_response_code(409);
                }
                else{
                    $queryinsert = "INSERT INTO `users_ratings`(`rating_id`, `user_id`, `time`, `product_id`) VALUES (:ratingID,:userID,:time,:productID)";
                    $preparedinsert = $connection->prepare($queryinsert);
                    $preparedinsert->bindParam(":ratingID",$rate);
                    $preparedinsert->bindParam(":userID",$userID);
                    $preparedinsert->bindParam(":productID",$productID);
                    $preparedinsert->bindParam(":time",$time);
                    try{
                        $result = $preparedinsert->execute();
                    }
                    catch(PDOException $e){
                        http_response_code(500);
                    }
                    if($result){
                        http_response_code(201);
                    }
                    else{
                        http_response_code(422);
                    }
                }
            }
        }
    }
    else{
        http_response_code(401);
    }
}
else{
    http_response_code(403);
}
