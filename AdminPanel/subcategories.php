<?php include "head.php";
$query = "SELECT * FROM subcategories";
if(isset($_GET['del'])){
    $delid = $_GET['del'];
    $querydel = "DELETE FROM categories_subcategories_types WHERE subcategory_id = $delid";
    try{
        $connection->exec($querydel);
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
    $delid = $_GET['del'];
    $querydel = "DELETE FROM subcategories WHERE subcategory_id = $delid";
    try{
        $connection->exec($querydel);
        echo "Deletion successfully completed";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
}
if(isset($_POST['addsubcategory'])){
    $subcategory = $_POST['subcategory'];
    $insertquery = "INSERT INTO subcategories VALUES('','$subcategory')";
    try{
        $connection->exec($insertquery);
        echo "Insert completed successfully";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Error while inserting the new subcategory";
    }
}
if(isset($_POST['updatesubcategory'])){
    $subcategory = $_POST['subcategory'];
    $id = $_POST['updateid'];
    $updatequery = "UPDATE `subcategories` SET `name`='$subcategory' WHERE subcategory_id = $id";
    try{
        $connection->exec($updatequery);
        echo "Update completed successfully";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Error while updating the subcategory";
    }
}
$result = $connection->query($query)->fetchAll();
?>
<div class="container">
<h2>Add a new subcategory</h2>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
<div class="row">
<div class="col">
<label>Subcategory</label>
<input name="subcategory" id="subcategory" class="form-control"/>
<input type="hidden" name="updateid" id="updateid"/>
<input type="submit" name="addsubcategory" style="margin-top:2rem" class="btn btn-info" value="Add a new subcategory"></button>
<input type="submit" name="updatesubcategory" style="margin-top:2rem" class="btn btn-info" value="Update subcategory"></button>
</form>
</div>
<h2>Subcategory</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Subcategory</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($result as $row): ?>
                <tr>
                    <td><?= $row->name?></td>
                    <td><a href="#" data-up="<?= $row->subcategory_id?>" class="update">Update</a></td>
                    <td><a href="subcategories.php?del=<?=$row->subcategory_id?>" data-del="<?= $row->subcategory_id?>" class="delete">Delete</a></td>
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
        url : "ajaxsubcategories.php",
        type : "POST",
        data : {
            btn : true,
            id : id
        },
        success : function(data){
            var podaci = data.userdata;
            console.log(podaci);
            $("#subcategory").val(podaci[0].name);
            $("#updateid").val(podaci[0].subcategory_id);
        }
    });
    });
});
</script>
</body>
