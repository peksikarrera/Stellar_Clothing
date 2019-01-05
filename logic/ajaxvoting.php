<?php
@session_start();
include("connection.php");
if(isset($_POST['vote'])){
    $vote = $_POST['vote'];
    if(isset($_SESSION['user'])){
        $query = "SELECT * FROM ((users u INNER JOIN poll_users_options puo ON u.user_id = puo.user_id) INNER JOIN poll p ON p.poll_id = puo.poll_id) INNER JOIN options o ON o.option_id = puo.option_id WHERE p.active=1 and email = '{$_SESSION['user']->email}'";
        try{
            $result = $connection->query($query);
                if($result->rowcount()==1){
                    $code = 409;
                }
                else{
                    $datetime = date("Y-m-d H:i:s");
                    $query = "INSERT INTO poll_users_options VALUES((SELECT poll_id FROM poll WHERE active=1),{$_SESSION['user']->user_id},'$datetime',(SELECT option_id FROM options WHERE name='$vote'))";
                    $code = 201;
                }
        }
        catch(PDOException $e){
            $code = 500;
        }
        try{
            $connection->query($query);
            $query = "SELECT COUNT(*) as numberofvotes,o.name as name FROM (options o INNER JOIN poll_users_options puo ON o.option_id = puo.option_id) INNER JOIN poll p ON p.poll_id = puo.poll_id WHERE active=1 GROUP BY o.option_id";
            $result = $connection->query($query);
            $rows = $result->fetchAll();
            header('Content-type:application/json');
            echo json_encode($rows);
        }
        catch(PDOException $e){
            $code=500;
        }
    }
    else{
        $code = 401;
    }
}
http_response_code($code);