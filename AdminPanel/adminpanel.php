<?php include "head.php";
$querynoproducts = "SELECT DISTINCT COUNT(*) as prodnum FROM products";
$numproducts = $connection->query($querynoproducts)->fetch();
$queryavgrating = "SELECT ROUND(AVG(rating_id),2)as avgrating FROM users_ratings";
$avgrating = $connection->query($queryavgrating)->fetch();
$queryavgprice = "SELECT ROUND(AVG(net_price),2) as avgprice FROM prices";
$avgprice = $connection->query($queryavgprice)->fetch();
$querynousers = "SELECT COUNT(*) as nousers FROM users";
$nousers = $connection->query($querynousers)->fetch();
$querynoorders = "SELECT COUNT(*) as noord FROM orders";
$noorders = $connection->query($querynoorders)->fetch();
 ?>
<div class="container">
        <h2>Admin panel home page</h2>
        <p>Welcome to the admin panel!</p>
        <br/>
        <p>Number of products in database: <?=$numproducts->prodnum?></p>
        <p>Average rating for all products: <?=$avgrating->avgrating?></p>
        <p>Average price for all products: <?=$avgprice->avgprice?></p>
        <p>Number of registered users: <?=$nousers->nousers?></p>
        <p>Number of orders placed: <?=$noorders->noord?></p>
</div>
</body>

</html>