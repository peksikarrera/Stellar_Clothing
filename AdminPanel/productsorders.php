<?php
include "head.php";
if(isset($_GET['del'])){
    $delid = $_GET['del'];
    $querydel = "DELETE FROM orders_products WHERE order_id = $delid";
    try{
        $connection->exec($querydel);
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
    $delid = $_GET['del'];
    $querydel = "DELETE FROM orders WHERE order_id = $delid";
    try{
        $connection->exec($querydel);
        echo "Deletion successfully completed";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
}
if(isset($_GET['order'])){
$orderID = $_GET['order'];
$query = "SELECT * FROM (((`orders_products` op INNER JOIN orders o ON o.order_id = op.order_id) INNER JOIN colors c ON c.color_id = op.color_id) INNER JOIN sizes s ON op.size_id = s.size_id) INNER JOIN products p ON p.product_id = op.product_id WHERE o.order_id = $orderID";
$result = $connection->query($query);
}
?>
<div class="container">
<h2>Products ordered</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product name</th>
                    <th>Description</th>
                    <th>Size</th>
                    <th>Color</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($result as $row): ?>
                <tr>
                    <td><?= $row->order_id ?></td>
                    <td><?= $row->name?></td>
                    <td><?= $row->description ?></td>
                    <td><?= $row->size?></td>
                    <td><?= $row->color?></td>
                    <td><?= $row->quantity?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>

</html>