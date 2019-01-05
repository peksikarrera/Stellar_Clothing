<?php include "head.php";
$query = "SELECT *,c.name as categoryname, p.name as productname, t.name as typename, s.name as subcategoryname FROM (((products p INNER JOIN categories_subcategories_types cst ON p.product_id = cst.product_id) INNER JOIN categories c ON c.category_id = cst.category_id) INNER JOIN subcategories s ON s.subcategory_id = cst.subcategory_id) INNER JOIN types t ON t.type_id = cst.type_id";
if(isset($_GET['del'])){
    $delid = $_GET['del'];
    $querydel = "DELETE FROM users_ratings WHERE product_id = $delid";
    try{
        $connection->exec($querydel);
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
    $querydel = "DELETE FROM products_sizes_colors WHERE product_id = $delid";
    try{
        $connection->exec($querydel);
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
    $querydel = "DELETE FROM productphotos WHERE product_id = $delid";
    try{
        $connection->exec($querydel);
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
    $querydel = "DELETE FROM prices WHERE product_id = $delid";
    try{
        $connection->exec($querydel);
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
    $querydel = "DELETE FROM orders_products WHERE product_id = $delid";
    try{
        $connection->exec($querydel);
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
    $querydel = "DELETE FROM categories_subcategories_types WHERE product_id = $delid";
    try{
        $connection->exec($querydel);
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
    $querydel = "DELETE FROM products WHERE product_id = $delid";
    try{
        $connection->exec($querydel);
        echo "Deletion succeeded";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
}
$querycategories = "SELECT * FROM categories";
$querysubcategories = "SELECT * FROM subcategories";
$querytypes = "SELECT * FROM types";
$result1 = $connection->query($querycategories)->fetchAll();
$result2 = $connection->query($querysubcategories)->fetchAll();
$result3 = $connection->query($querytypes)->fetchAll();
if(isset($_POST['addproduct'])){
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = (int)$_POST['category'];
    $subcategory = (int)$_POST['subcategory'];
    $type = (int)$_POST['type'];
    $queryinsert = "INSERT INTO `products`(`product_id`, `name`, `description`) VALUES ('','$name','$description')";
    try{
        $connection->exec($queryinsert);
    }
    catch(PDOException $e){
        echo "Error while inserting";
        $e->getMessage();
    }
    $lastid = $connection->lastinsertid();
    $queryinsertmore = "INSERT INTO `categories_subcategories_types`(`category_id`, `subcategory_id`, `type_id`, `product_id`) VALUES ($category,$subcategory,$type,$lastid)";
    try{
        $connection->exec($queryinsertmore);
        echo "Insert completed successfully";
    }
    catch(PDOException $e){
        echo "Error while inserting";
        $e->getMessage();
    }
}
if(isset($_POST['updateproduct'])){
    $name = $_POST['name'];
    $description = $_POST['description'];
    $id = $_POST['updateid'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = (int)$_POST['category'];
    $subcategory = (int)$_POST['subcategory'];
    $type = (int)$_POST['type'];
    $queryupdate = "UPDATE products SET name = '$name', description = '$description' WHERE product_id = $id";
    try{
    $connection->exec($queryupdate);
        echo "Update completed successfully";
    }
    catch(PDOException $e){
        echo "Error while updating";
        echo $e->getMessage();
    }
    $queryupdatemore = "UPDATE `categories_subcategories_types` SET `category_id`= $category,`subcategory_id`= $subcategory,`type_id`= $type WHERE product_id = $id";
}
$result = $connection->query($query)->fetchAll();
?>
<div class="container">
<h2>Insert a new product</h2>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
<label>Name</label>
<input name="name" id="name" class="form-control"/>
<label>Description</label>
<input name="description" id="description" class="form-control"/>
<label>Category</label>
<select name="category" id="category" class="form-control">
<?php foreach($result1 as $row): ?>
    <option value="<?= $row->category_id?>"><?= $row->name?></option>
<?php endforeach; ?>
</select>
<label>Subcategory</label>
<select name="subcategory" id="subcategory" class="form-control">
<?php foreach($result2 as $row): ?>
    <option value="<?= $row->subcategory_id?>"><?= $row->name?></option>
<?php endforeach; ?>
</select>
<label>Type</label>
<select name="type" id="type" class="form-control">
<?php foreach($result3 as $row): ?>
    <option value="<?= $row->type_id?>"><?= $row->name?></option>
<?php endforeach; ?>
</select>
<input type="hidden" name="updateid" id="updateid"/>
<input type="submit" name="addproduct" style="margin-top:2rem" class="btn btn-info" value="Add a new product"></button>
<input type="submit" name="updateproduct" style="margin-top:2rem" class="btn btn-info" value="Update product"></button>
</form>
<h2>Products</h2>
        <table class="table table-striped">
            <p>Here is a list of products. By clicking on the link photos, you will go to the page filled with the product's photos. By clicking on the rating, you will go to the page completed with the information about users who rated the selected product. </p>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Subcategory</th>
                    <th>Type</th>
                    <th>Rating</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($result as $row): ?>
                <tr>
                    <td><?= $row->productname?></td>
                    <td><?= $row->description?></td>
                    <td><?= $row->categoryname?></td>
                    <td><?= $row->subcategoryname?></td>
                    <td><?= $row->typename?></td>
                    <?php $queryrating = "SELECT ROUND(AVG(rating_id),2) as rating FROM `users_ratings` WHERE product_id = $row->product_id";
                    $result = $connection->query($queryrating)->fetch();
                    if(empty($result->rating)) echo "<td>/</td>" ; else echo '<td><a href="ratings.php?prod='.$row->product_id.'">'.$result->rating.'</td>';
                    ?>
                    <td><a href="productphotos.php?productID=<?= $row->product_id?>">Photos</a></td>
                    <td><a href="#" class="update" data-up="<?= $row->product_id?>">Update</a></td>
                    <td><a href="products.php?del=<?=$row->product_id?>" class="delete" data-del="<?= $row->product_id?>">Delete</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
    $(document).ready(function(){
    $(".update").on("click",function(e){
    var id = $(this).data("up");
    console.log(id);
    $.ajax({
        url : "ajaxproducts.php",
        type : "POST",
        data : {
            btn : true,
            id : id
        },
        success : function(data){
            var podaci = data.userdata;
            console.log(podaci);
            $("#name").val(podaci[0].name);
            $("#description").val(podaci[0].description);
            $("#updateid").val(podaci[0].product_id);
            $("#subcategory").val(podaci[0].subcategory_id);
            $("#category").val(podaci[0].category_id);
            $("#type").val(podaci[0].type_id);
        }
    });
    });
});
</script>
</body>

</html>