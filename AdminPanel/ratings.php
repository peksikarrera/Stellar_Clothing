<?php include "head.php";
if(isset($_GET['prod'])){
    $productID = $_GET['prod'];
}
$query = "SELECT * FROM users_ratings ur INNER JOIN users u ON u.user_id = ur.user_id WHERE product_id = $productID";
$result = $connection->query($query)->fetchAll();
?>
<div class="container">
<h2>People who rated the product</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Email</th>
                    <th>Rating</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($result as $row): ?>
                <tr>
                    <td><?= $row->user_id ?></td>
                    <td><?= $row->firstname?></td>
                    <td><?= $row->lastname?></td>
                    <td><?= $row->email?></td>
                    <td><?= $row->rating_id?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>

</html>