<?php
include "connection.php";
session_start();
if(isset($_POST['orderbtn'])){
    if(empty($_SESSION['user'])){
        http_response_code(401);
    }
    else{
        if(isset($_SESSION['products'])){
            $products = $_SESSION['products'];
            $datetime = date("Y-m-d H:i:s");
            $usrid = $_SESSION['user']->user_id;
            $query = "INSERT INTO `orders`(`order_id`, `time`, `user_id`) VALUES ('','$datetime',$usrid)";
            try{
                $connection->exec($query);
                $lastID = $connection->lastinsertid();
            }
            catch(PDOException $e){
                http_response_code(500);
            }
            $connection->beginTransaction(); 
            foreach($products as $product){
                $productID = $product['productID'];
                $colorID = $product['colorID'];
                $sizeID = $product['sizeID'];
                $quantity = $product['quantity'];
                $query = "SELECT quantity FROM `products_sizes_colors` WHERE product_id = $productID AND size_id = $sizeID AND color_id = $colorID";
                $result = $connection->query($query)->fetch();
                if($result->quantity - $quantity >=0){
                    $insertquery = "INSERT INTO `orders_products`(`order_id`, `product_id`, `color_id`, `size_id`, `quantity`) VALUES ($lastID,$productID,$colorID,$sizeID,$quantity)";
                    try{
                        $connection->exec($insertquery);
                        unset($_SESSION['products']);
                        $_SESSION['products'] = [];
                        http_response_code(201);
                        $changequantity = "UPDATE products_sizes_colors SET quantity = quantity - $quantity WHERE product_id = $productID AND color_id = $colorID AND size_id = $sizeID";
                        try{
                            $connection->exec($changequantity);
                        }
                        catch(PDOException $e){
                            http_response_code(500);
                        }
                    }
                    catch(PDOException $e){
                        http_response_code(500);
                    }
                }
                else{
                    http_response_code(422);
                }
            }
            $connection->commit(); 
        }
        else{
            http_response_code(404);
        }
    }
}
else{
    http_response_code(401);
}