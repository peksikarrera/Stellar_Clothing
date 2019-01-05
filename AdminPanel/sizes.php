<?php include "head.php";
$query = "SELECT * FROM sizes";
if(isset($_GET['del'])){
    $delid = $_GET['del'];
    $querydel = "DELETE FROM products_sizes_colors WHERE size_id = $delid";
    try{
        $connection->exec($querydel);
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
    $delid = $_GET['del'];
    $querydel = "DELETE FROM sizes WHERE size_id = $delid";
    try{
        $connection->exec($querydel);
        echo "Deletion successfully completed";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
}
if(isset($_POST['addsize'])){
    $size = $_POST['size'];
    $insertquery = "INSERT INTO sizes VALUES('','$size')";
    try{
        $connection->exec($insertquery);
        echo "Insert completed successfully";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Error while inserting the new size";
    }
}
if(isset($_POST['updatesize'])){
    $size = $_POST['size'];
    $id = $_POST['updateid'];
    $insertquery = "UPDATE sizes SET size = '$size' WHERE size_id = $id";
    try{
        $connection->exec($insertquery);
        echo "Update completed successfully";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Error while updating the size";
    }
}
$result = $connection->query($query)->fetchAll();
?>
<div class="container">
<h2>Add a new size</h2>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
<div class="row">
<div class="col">
<label>Size</label>
<input name="size" id="size" class="form-control"/>
<input type="hidden" name="updateid" id="updateid"/>
<input type="submit" name="addsize" style="margin-top:2rem" class="btn btn-info" value="Add a new size"></button>
<input type="submit" name="updatesize" style="margin-top:2rem" class="btn btn-info" value="Update size"></button>
</form>
</div>
<h2>Sizes</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Size</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($result as $row): ?>
                <tr>
                    <td><?= $row->size?></td>
                    <td><a href="#" class="update" data-del="<?= $row->size_id?>" data-up="<?= $row->size_id?>">Update</a></td>
                    <td><a href="sizes.php?del=<?=$row->size_id?>" class="delete" data-del="<?= $row->size_id?>" data-up="<?= $row->size_id?>">Delete</a></td>
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
        url : "ajaxsizesfetch.php",
        type : "POST",
        data : {
            btn : true,
            id : id
        },
        success : function(data){
            var podaci = data.userdata;
            console.log(podaci);
            $("#size").val(podaci[0].size);
            $("#updateid").val(podaci[0].size_id);
        }
    });
    });
});
</script>
</body>

</html>