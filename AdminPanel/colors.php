<?php include "head.php";
$query = "SELECT * FROM colors";
if(isset($_GET['del'])){
    $delid = $_GET['del'];
    $querydel = "DELETE FROM products_sizes_colors WHERE color_id = $delid";
    try{
        $connection->exec($querydel);
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
    $delid = $_GET['del'];
    $querydel = "DELETE FROM colors WHERE color_id = $delid";
    try{
        $connection->exec($querydel);
        echo "Deletion successfully completed";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
}
if(isset($_POST['addcolor'])){
    $color = $_POST['color'];
    $insertquery = "INSERT INTO colors VALUES('','$color')";
    try{
        $connection->exec($insertquery);
        echo "Insert completed successfully";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Error while inserting the new color";
    }
}
if(isset($_POST['updatecolor'])){
    $color = $_POST['color'];
    $id = $_POST['updateid'];
    $insertquery = "UPDATE colors SET color = '$color' WHERE color_id = $id";
    try{
        $connection->exec($insertquery);
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
<h2>Add a new color</h2>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
<div class="row">
<div class="col">
<label>Size</label>
<input name="color" id="color" class="form-control"/>
<input type="hidden" name="updateid" id="updateid"/>
<input type="submit" name="addcolor" style="margin-top:2rem" class="btn btn-info" value="Add a new color"></button>
<input type="submit" name="updatecolor" style="margin-top:2rem" class="btn btn-info" value="Update color"></button>
</form>
</div>
<h2>Colors</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Color</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($result as $row): ?>
                <tr>
                    <td><?= $row->color?></td>
                    <td><a class="update" data-up="<?= $row->color_id?>"  href="#">Update</a></td>
                    <td><a class="delete" data-del="<?= $row->color_id?>" href="colors.php?del=<?=$row->color_id?>">Delete</a></td>
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
        url : "ajaxcolorsfetch.php",
        type : "POST",
        data : {
            btn : true,
            id : id
        },
        success : function(data){
            var podaci = data.userdata;
            console.log(podaci);
            $("#color").val(podaci[0].color);
            $("#updateid").val(podaci[0].color_id);
        }
    });
    });
});
</script>
</body>

</html>