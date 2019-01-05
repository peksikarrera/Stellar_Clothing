<?php include "head.php";
$query = "SELECT * FROM prices pr INNER JOIN products p ON p.product_id = pr.product_Id";
$queryproducts = "SELECT * FROM products";
$productsresult = $connection->query($queryproducts)->fetchAll();
if(isset($_GET['del'])){
    $delid = $_GET['del'];
    $querydel = "DELETE FROM prices WHERE price_id = $delid";
    try{
        $connection->exec($querydel);
        echo "Delete operation succeeded";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
}
if(isset($_POST['addprice'])){
    $price = (double)$_POST['net_price'];
    if(empty($_POST['optradio'])) $active = 0 ; else $active = (int)$_POST['optradio'];
    $selectedproduct = (int)$_POST['products'];
    if(empty($_POST['discount']))
    $insertquery = "INSERT INTO `prices`(`price_id`, `net_price`, `discount`, `active`, `product_id`) VALUES ('',$price,null,$active,$selectedproduct)";
    else {
        $discount = (double)$_POST['discount'];
        $insertquery = "INSERT INTO `prices`(`price_id`, `net_price`, `discount`, `active`, `product_id`) VALUES ('',$price,$discount,$active,$selectedproduct)";
    }
    try{
    $connection->exec($insertquery);
    echo "Insert completed successfully";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Insert failed";
    }
}
if(isset($_POST['updateprice'])){
    $price = (double)$_POST['net_price'];
    if(empty($_POST['optradio'])) $active = 0 ; else $active = (int)$_POST['optradio'];
    $selectedproduct = (int)$_POST['products'];
    $id = $_POST['updateid'];
    if(empty($_POST['discount']))
    $updatequery = "UPDATE `prices` SET net_price= $price,`discount`= null,`active`= $active,`product_id`= $selectedproduct WHERE price_id= $id";
    else {
        $discount = (double)$_POST['discount'];
        $updatequery = "UPDATE `prices` SET net_price= $price,`discount`= $discount,`active`= $active,`product_id`= $selectedproduct WHERE price_id= $id";
    }
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
?>
<div class="container">
<h2>Add a new price</h2>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
<div class="row">
<div class="col">
<label>Net price</label>
<input name="net_price" id="net_price" class="form-control"/>
<label>Discount&nbsp(you can leave discount field blank)</label>
<input name="discount" id="discount" class="form-control"/>
<span id="options"></span>
<label for="chbactive">Active:</label>
<label class="radio-inline"><input type="radio" value="1" id="yes" name="optradio">Yes</label>
<label class="radio-inline"><input type="radio" value="0" id="no" name="optradio">No</label>
<div class="alert alert-info">
Kada dodajete i menjate cenu za proizvod, morate pre toga za aktuelnu cenu, da stavite active : no. Za novu cenu (ili neku drugu cenu koju zelite da postanete aktuelna) morate obeleziti active : yes
</div>
</div>
<label for="chbactive">Select product</label>
<select name="products" id="products" class="form-control">
    <?php foreach($productsresult as $product): ?>
    <option value="<?=$product->product_id?>"><?=$product->product_id . ". " . $product->name . " - " . $product->description?></option>
    <?php endforeach; ?>
</select>
<input type="hidden" name="updateid" id="updateid"/>
<input type="submit" name="addprice" style="margin-top:2rem" class="btn btn-info" value="Add a new price"></button>
<input type="submit" name="updateprice" style="margin-top:2rem" class="btn btn-info" value="Update price"></button>
</form>
</div>
<h2>Prices</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product name</th>
                    <th>Description</th>
                    <th>Net price</th>
                    <th>Discount</th>
                    <th>Active</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($result as $row): ?>
                <tr>
                    <td><?= $row->name ?></td>
                    <td><?= $row->description ?></td>
                    <td><?= $row->net_price?></td>
                    <td><?= $row->discount?></td>
                    <td><?php if($row->active == 1) echo "Yes" ; else echo "No";?></td>
                    <td><a class="update" data-up="<?= $row->price_id?>"  href="#">Update</a></td>
                    <td><a class="delete" data-del="<?= $row->price_id?>" href="prices.php?del=<?=$row->price_id?>">Delete</a></td>
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
        url : "ajaxpricesfetch.php",
        type : "POST",
        data : {
            btn : true,
            id : id
        },
        success : function(data){
            var podaci = data.userdata;
            console.log(podaci);
            $("#net_price").val(podaci[0].net_price);
            $("#discount").val(podaci[0].discount);
            $("#active").val(podaci[0].active);
            $("#updateid").val(id);
            if(podaci[0].active == "1"){
                $('#yes').prop('checked', true);
            }
            else{
                $('#no').prop('checked', true);
            }
            $("#products").val(podaci[0].product_id);
        }
    });
    });
});
</script>
</body>

</html>