<?php include "head.php";
$query = "SELECT * FROM categories";
if(isset($_GET['del'])){
    $delid = $_GET['del'];
    $querydel = "DELETE FROM categories_subcategories_types WHERE category_id = $delid";
    try{
        $connection->exec($querydel);
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
    $delid = $_GET['del'];
    $querydel = "DELETE FROM categories WHERE category_id = $delid";
    try{
        $connection->exec($querydel);
        echo "Deletion successfully completed";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
}
if(isset($_POST['addcategory'])){
    $category = $_POST['category'];
    $insertquery = "INSERT INTO categories VALUES('','$category')";
    try{
        $connection->exec($insertquery);
        echo "Insert completed successfully";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Error while inserting the new color";
    }
}
if(isset($_POST['updatecategory'])){
    $category = $_POST['category'];
    $id = $_POST['updateid'];
    $updatequery = "UPDATE categories SET name = '$category' WHERE category_id = $id";
    try{
        $connection->exec($updatequery);
        echo "Update completed successfully";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Error while updating the color";
    }
}
$result = $connection->query($query)->fetchAll();
?>
<div class="container">
<h2>Add a new category</h2>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
<div class="row">
<div class="col">
<label>Category</label>
<input name="category" id="category" class="form-control"/>
<input type="hidden" name="updateid" id="updateid"/>
<input type="submit" name="addcategory" style="margin-top:2rem" class="btn btn-info" value="Add a new category"></button>
<input type="submit" name="updatecategory" style="margin-top:2rem" class="btn btn-info" value="Update category"></button>
</form>
</div>
<h2>Categories</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Category</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($result as $row): ?>
                <tr>
                    <td><?= $row->name?></td>
                    <td><a href="#" data-up="<?= $row->category_id?>" class="update">Update</a></td>
                    <td><a href="categories.php?del=<?=$row->category_id?>" data-del="<?= $row->category_id?>" class="delete">Delete</a></td>
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
        url : "ajaxcategoriesfetch.php",
        type : "POST",
        data : {
            btn : true,
            id : id
        },
        success : function(data){
            var podaci = data.userdata;
            console.log(podaci);
            $("#category").val(podaci[0].name);
            $("#updateid").val(podaci[0].category_id);
        }
    });
    });
});
</script>
</body>

</html>