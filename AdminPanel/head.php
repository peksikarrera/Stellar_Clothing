<?php
    session_start();
    include "../logic/connection.php";
    if(isset($_SESSION['user'])){
        if($_SESSION['user']->rolename != 'administrator'){
            header("Location:../index.php");
        }
    }
    else{
        header("Location:../index.php");
    }
?>
<html>
<head>
    <title>Swim Wear a E-Commerce online Shopping Category Flat Bootstrap Responsive Website Template | Home :: w3layouts</title>
    <link href="../css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
    <link href="../css/adminpanelstyle.css" rel="stylesheet" type="text/css" media="all" />
    <link href="../css/owl.carousel.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="../js/jquery.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap-3.1.1.min.js"></script>
</head>
<body>
    <div class="container-fluid">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                <a class="navbar-brand" href="adminpanel.php">Stellar Clothing Admin Panel</a>
                </div>
                <ul class="nav navbar-nav">
                <li class="active"><a href="adminpanel.php">Home</a></li>
                <li><a href="users.php">Users</a></li>
                <li><a href="roles.php">Roles</a></li>
                <li><a href="headingmenu.php">Heading menu</a></li>
                <li><a href="footermenu.php">Footer menu</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="sizes.php">Sizes</a></li>
                <li><a href="colors.php">Colors</a></li>
                <li><a href="prices.php">Prices</a></li>
                <li><a href="allitems.php">All items</a></li>
                <li><a href="poll.php">Poll</a></li>
                <li><a href="categories.php">Categories</a></li>
                <li><a href="subcategories.php">Subcategories</a></li>
                <li><a href="types.php">Types</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="slider.php">Slider</a></li>
                <li><a href="../index.php">Back to home page</a></li>
                <li><a href="../logic/logout.php">Log out</a></li>
                </ul>
            </div>
        </nav>