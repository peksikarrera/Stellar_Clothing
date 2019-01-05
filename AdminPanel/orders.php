<?php
include "head.php";
$query = "SELECT * FROM users u INNER JOIN orders o ON o.user_id = u.user_id";
$result = $connection->query($query);
?>
<div class="container">
<h2>Orders</h2>
<p>Even the administrator isn't allowed to manipulate with the orders data. An administrator is only allowed to view and delete the orders.</p>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Order placed on</th>
                    <th>User ID</th>
                    <th>First name</th>
                    <th>Lastname</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($result as $row): ?>
                <tr>
                    <td><?= $row->order_id ?></td>
                    <td><?= $row->time ?></td>
                    <td><?= $row->user_id ?></td>
                    <td><?= $row->firstname?></td>
                    <td><?= $row->lastname?></td>
                    <td><?= $row->email?></td>
                    <td><a href="productsorders.php?order=<?=$row->order_id?>">Products</a></td>
                    <td><a href="orders.php?del=<?=$row->order_id?>">Delete</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>

</html>