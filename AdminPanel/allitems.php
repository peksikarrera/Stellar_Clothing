<?php
include "head.php";
$query = "SELECT p.product_id as productID, p.name as productname, p.description as productdescription, c.color as color, s.size as size, ca.name as categoryname, su.name as subcategoryname, t.name as typename, quantity, active, productitem_id FROM ((((((products p INNER JOIN products_sizes_colors psc ON psc.product_id = p.product_id) INNER JOIN sizes s ON psc.size_id = s.size_id) INNER JOIN colors c ON c.color_id = psc.color_id) INNER JOIN categories_subcategories_types cst ON cst.product_id = p.product_id) INNER JOIN categories ca ON ca.category_id = cst.category_id) INNER JOIN subcategories su ON su.subcategory_id = cst.subcategory_id) INNER JOIN types t ON t.type_id = cst.type_id ORDER BY p.product_id";
if(isset($_GET['del'])){
    $delid = $_GET['del'];
    $querydel = "DELETE FROM products_sizes_colors WHERE productitem_id = $delid";
    try{
        $connection->exec($querydel);
        echo "Deletion successfully completed";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
}
if(isset($_POST['additem'])){
    $product = (int)$_POST['products'];
    $color = (int)$_POST['colors'];
    $size = (int)$_POST['sizes'];
    $quantity = (int)$_POST['quantity'];
    if(empty($_POST['optradio'])) $active = 0 ; else $active = (int)$_POST['optradio'];
    $insertquery = "INSERT INTO `products_sizes_colors`(`productitem_id`, `product_id`, `size_id`, `color_id`, `active`, `quantity`) VALUES ('',$product,$size,$color,$active,$quantity)";
    try{
        $connection->exec($insertquery);
        echo "Insert completed successfully";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Insert failed";
    }
}
if(isset($_POST['updateitem'])){
    $product = (int)$_POST['products'];
    $color = (int)$_POST['colors'];
    $size = (int)$_POST['sizes'];
    $quantity = (int)$_POST['quantity'];
    $id = (int)$_POST['updateid'];
    if(empty($_POST['optradio'])) $active = 0 ; else $active = (int)$_POST['optradio'];
    $updatequery = "UPDATE `products_sizes_colors` SET `product_id`= $product,`size_id`= $size,`color_id`=$color,`active`=$active,`quantity`=$quantity WHERE productitem_id= $id";
    try{
        $connection->exec($updatequery);
        echo "Update completed successfully";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Update failed";
    }
}
$result = $connection->query($query)->fetchAll();
$queryproducts = "SELECT * FROM products";
$querycolors = "SELECT * FROM colors";
$querysizes = "SELECT * FROM sizes";
$productresult = $connection->query($queryproducts)->fetchAll();
$colorresult = $connection->query($querycolors)->fetchAll();
$sizeresult = $connection->query($querysizes)->fetchAll();
?>
<div class="container">
<h2>Add a new item</h2>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
<div class="row">
<div class="col">
<label>Product</label>
<select name="products" id="products" class="form-control">
    <?php foreach($productresult as $product): ?>
    <option value="<?=$product->product_id?>"><?=$product->product_id . ". " . $product->name . " - " . $product->description?></option>
    <?php endforeach; ?>
</select>
<label>Color</label>
<select name="colors" id="colors" class="form-control">
    <?php foreach($colorresult as $color): ?>
    <option value="<?=$color->color_id?>"><?=$color->color?></option>
    <?php endforeach; ?>
</select>
<label>Size</label>
<select name="sizes" id="sizes" class="form-control">
    <?php foreach($sizeresult as $size): ?>
    <option value="<?=$size->size_id?>"><?=$size->size?></option>
    <?php endforeach; ?>
</select>
<label>Quantity</label>
<input type="text" name="quantity" id="quantity" class="form-control"/>
<label for="chbactive">Active:</label>
<label class="radio-inline"><input type="radio" value="1" id="yes" name="optradio">Yes</label>
<label class="radio-inline"><input type="radio" value="0" id="no" name="optradio">No</label>
</div>
<input type="hidden" name="updateid" id="updateid"/>
<input type="submit" name="additem" style="margin-top:2rem" class="btn btn-info" value="Add a new item"></button>
<input type="submit" name="updateitem" style="margin-top:2rem" class="btn btn-info" value="Update item"></button>
</form>
</div>
<h2>All items</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product name</th>
                    <th>Description</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Category</th>
                    <th>Subcategory</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Available</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($result as $row): ?>
                <tr>
                    <td><?= $row->productID ?></td>
                    <td><?= $row->productname ?></td>
                    <td><?= $row->productdescription ?></td>
                    <td><?= $row->color?></td>
                    <td><?= $row->size?></td>
                    <td><?= $row->categoryname?></td>
                    <td><?= $row->subcategoryname?></td>
                    <td><?= $row->typename?></td>
                    <td><?= $row->quantity?></td>
                    <td><?php if(empty($row->active)) echo "No" ; else echo "Yes" ?></td>
                    <td><a href="#" data-up="<?= $row->productitem_id?>" class="update">Update</a></td>
                    <td><a href="allitems.php?del=<?=$row->productitem_id?>" data-del="<?= $row->productitem_id?>" class="delete">Delete</a></td>
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
        url : "ajaxfetchallit.php",
        type : "POST",
        data : {
            btn : true,
            id : id
        },
        success : function(data){
            var podaci = data.userdata;
            console.log(podaci);
            $("#updateid").val(id);
            if(podaci[0].active == "1"){
                $('#yes').prop('checked', true);
            }
            else{
                $('#no').prop('checked', true);
            }
            $("#colors").val(podaci[0].color_id);
            $("#sizes").val(podaci[0].size_id);
            $("#quantity").val(podaci[0].quantity);
            $("#products").val(podaci[0].product_id);
        }
    });
    });
});
</script>
</body>

</html>