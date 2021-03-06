<?php
    session_start();
    include("logic/connection.php");
    $page = "";
    if(isset($_GET['page'])){
        $page = $_GET['page'];
    }
    include("template/head.php");
    include("template/heading.php");
    switch($page){
        case "home":
            include("home.php");
            break;
        case "account":
            include("account.php");
            break;
        case "checkout":
            include("checkout.php");
            break;
        case "contact":
            include("contact.php");
            break;
        case "login":
            include("login.php");
            break;
        case "products":
            include("products.php");
            break;
        case "single":
            include("single.php");
            break;
        case "author":
            include("author.php");
            break;
        default: 
            include("home.php");
    }
    include("template/footer.php");
?>