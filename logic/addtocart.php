<?php


session_start();
include "connection.php";
if(isset($_POST['addcart'])){
    if(isset($_SESSION['user'])){
        $productID = $_POST['productID'];
        $quantity = $_POST['quantity'];
        $colorID = $_POST['colorID'];
        $sizeID = $_POST['sizeID'];
        $rawprice = $_POST['price'];
        $price = (double)$rawprice*(int)$quantity;
        $queryavailablecolor = "SELECT * FROM products p INNER JOIN products_sizes_colors psz ON psz.product_id = p.product_id WHERE color_id=:colorID AND p.product_id=:productID AND quantity >= :quantity";
        $queryavailablesize = "SELECT * FROM products p INNER JOIN products_sizes_colors psz ON psz.product_id = p.product_id WHERE size_id=:sizeID AND p.product_id=:productID AND quantity >= :quantity";
        $queryavailable = "SELECT * FROM products p INNER JOIN products_sizes_colors psz ON psz.product_id = p.product_id WHERE size_id=:sizeID AND color_id =:colorID AND p.product_id=:productID AND quantity >= :quantity";
        $preparedquery1 = $connection->prepare($queryavailablecolor);
        $preparedquery1->bindParam(":productID",$productID);
        $preparedquery1->bindParam(":colorID",$colorID);
        $preparedquery1->bindParam(":quantity",$quantity);
        $errors = [];
        try{
          $preparedquery1->execute();
          if($preparedquery1->rowCount()==0){
            $errors[] = "A quantity of the color you chose is not available in our stock";
          }
        }   
        catch(PDOException $e){
            
        }
        $preparedquery2 = $connection->prepare($queryavailablesize);
        $preparedquery2->bindParam(":productID",$productID);
        $preparedquery2->bindParam(":sizeID",$sizeID);
        $preparedquery2->bindParam(":quantity",$quantity);
        try{
          $preparedquery2->execute();
          if($preparedquery2->rowCount()==0){
            $errors[] = "A quantity of the size you chose is not available in our stock";
          }
        } 
        catch(PDOException $e){
            
        }  
        if(count($errors)>0){
            echo json_encode(['message'=>$errors]);
        }
        $preparedquery3 = $connection->prepare($queryavailable);
        $preparedquery3->bindParam(":productID",$productID);
        $preparedquery3->bindParam(":sizeID",$sizeID);
        $preparedquery3->bindParam(":colorID",$colorID);
        $preparedquery3->bindParam(":quantity",$quantity);
        try{
          $preparedquery3->execute();
          if($preparedquery3->rowCount()==0){
            $message = "The size and the color you chose are not available in our stock. Please choose another combination";
            echo json_encode(['message'=>$message]);
          }
          else{
            $productdata = ['productID'=>$productID,'sizeID'=>$sizeID,'colorID'=>$colorID,'quantity'=>$quantity,'price'=>$price];
            $match = false;
            for($i=0;$i<count($_SESSION['products']);$i++){
              if($productdata['productID'] == $_SESSION['products'][$i]['productID']){
                if($productdata['sizeID'] == $_SESSION['products'][$i]['sizeID']){
                  if($productdata['colorID'] == $_SESSION['products'][$i]['colorID']){
                        (int)$_SESSION['products'][$i]['quantity']+=$quantity;
                        (double)$_SESSION['products'][$i]['price']+=(double)$rawprice*$quantity;
                        $match = true;
                      }
                    }
                }
            }
            if(!$match){
              array_push($_SESSION['products'],$productdata);
            }
          }
          echo json_encode('The product has been added to the shopping cart');
        } 
        catch(PDOException $e){
            
        }  
    }
    else{
        http_response_code(401);
    }
}
else{
    header("Location:../index.php");
}