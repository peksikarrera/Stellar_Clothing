<?php
include("connection.php");
if(isset($_GET['vercrypt'])){
    $token = $_GET['vercrypt'];
    $query = "SELECT * FROM users WHERE random = :random";
    $preparedquery = $connection->prepare($query);
    $preparedquery->bindParam(":random",$token);
    try{
        $result = $preparedquery->execute();
        if($preparedquery->rowcount()==1){
            $query = "UPDATE users SET active = 1 WHERE random = :random";
            $preparedquery = $connection->prepare($query);
            $preparedquery->bindParam(":random",$token);
            try{
                $result = $preparedquery->execute();
                if($result){
                    http_response_code(200);
                }
                else{
                    http_response_code(422);
                }
            }
            catch(PDOExeption $e){
                http_response_code(500);
            }
        }
        else{
            http_response_code(422);
        }
    }
    catch(PDOExeption $e){
        http_response_code(500);
    }
}
