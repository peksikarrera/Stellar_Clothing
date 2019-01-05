<?php @session_start();
include "connection.php";
if($_POST['loginbtn']){
    $rePasswd = '/^[0-9A-z\!\#\$\%\^\&\*\/]{7,20}$/';
    $password = md5($_POST['tbpassword']);
    $email = $_POST['tbemail'];
    $errors = [];
    if(!preg_match($rePasswd,$_POST['tbpassword'])){
        $errors[] = "Wrong password format";
    }
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $errors[] = "Wrong mail format";
    }
    if(count($errors)){
        http_response_code(422);
    }
    else{
        $query = "SELECT * FROM users INNER JOIN roles ON roles.role_id = users.role_id WHERE email = :email and password = :password and active=1";
        $preparedquery = $connection->prepare($query);
        $preparedquery->bindParam(":email",$email);
        $preparedquery->bindParam(":password",$password);
        try{
            $result = $preparedquery->execute();
            if($result){
                if($preparedquery->rowCount()==1){
                    http_response_code(200);
                    $user = $preparedquery->fetch();
                    $_SESSION['user'] = $user;
                    $_SESSION['products'] = [];
                    //header('Content-type:application/json');
                    //echo json_encode(['user'=>$user]);
                }
                else{
                    http_response_code(409);
                }
            }
        }
        catch(PDOException $x){
            echo $x->getMessage();
            http_response_code(500);
        }
    }
}