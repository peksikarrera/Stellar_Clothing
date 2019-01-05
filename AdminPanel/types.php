<?php include "head.php";
$query = "SELECT * FROM types";
if(isset($_GET['del'])){
    $delid = $_GET['del'];
    $querydel = "DELETE FROM categories_subcategories_types WHERE type_id = $delid";
    try{
        $connection->exec($querydel);
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
    $delid = $_GET['del'];
    $querydel = "DELETE FROM types WHERE type_id = $delid";
    try{
        $connection->exec($querydel);
        echo "Deletion successfully completed";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
}
if(isset($_POST['addtype'])){
    $type = $_POST['type'];
    $insertquery = "INSERT INTO types VALUES('','$type')";
    try{
        $connection->exec($insertquery);
        echo "Insert completed successfully";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Error while inserting the new type";
    }
}
if(isset($_POST['updatetype'])){
    $type = $_POST['type'];
    $id = $_POST['updateid'];
    $updatequery = "UPDATE types SET name = '$type' WHERE type_id = $id";
    try{
        $connection->exec($updatequery);
        echo "Update completed successfully";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Error while updating the type";
    }
}
$result = $connection->query($query)->fetchAll();
?>
<div class="container">
<h2>Add a new type</h2>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
<div class="row">
<div class="col">
<label>Type</label>
<input name="type" id="type" class="form-control"/>
<input type="hidden" name="updateid" id="updateid"/>
<input type="submit" name="addtype" style="margin-top:2rem" class="btn btn-info" value="Add a new type"></button>
<input type="submit" name="updatetype" style="margin-top:2rem" class="btn btn-info" value="Update type"></button>
</form>
</div>
<h2>Types</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($result as $row): ?>
                <tr>
                    <td><?= $row->name?></td>
                    <td><a href="#" data-up="<?= $row->type_id?>" class="update">Update</a></td>
                    <td><a href="types.php?del=<?=$row->type_id?>" data-del="<?= $row->type_id?>" class="delete">Delete</a></td>
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
        url : "ajaxtypesfetch.php",
        type : "POST",
        data : {
            btn : true,
            id : id
        },
        success : function(data){
            var podaci = data.userdata;
            console.log(podaci);
            $("#type").val(podaci[0].name);
            $("#updateid").val(podaci[0].type_id);
        }
    });
    });
});
</script>
</body>

</html>