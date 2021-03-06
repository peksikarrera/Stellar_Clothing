<?php
include "head.php";
if(isset($_GET['sl'])){
    $sl = $_GET['sl'];
    if(isset($_GET['del'])){
        $delid = $_GET['del'];
        $querydel = "DELETE FROM sliderphotos WHERE sliderphoto_id = $delid";
        try{
            $connection->exec($querydel);
            echo "Deletion successfully completed";
        }
        catch(PDOException $e){
            echo $e->getMessage();
            echo "Deletion failed";
        }
    }
    $query = "SELECT source,alt,sliderphoto_id as sliderphoto FROM slider s INNER JOIN sliderphotos sp ON s.slider_id = sp.slider_id WHERE s.slider_id=:sl";
    $preparedquery = $connection->prepare($query);
    $preparedquery->bindParam(":sl",$sl);
    if(isset($_POST['addphoto'])){
        $photo = $_FILES['photo'];
        $tmp_path = $photo['tmp_name'];
        $name = $photo['name'];
        $pathstoredatabase = "images/". rand() . time(). $name;
        $new_path = "../" . $pathstoredatabase;
        $alt = $_POST['alt'];
        if(move_uploaded_file($tmp_path,$new_path)){
            $queryinsert = "INSERT INTO `sliderphotos`(`sliderphoto_id`, `source`, `alt`, `slider_id`) VALUES ('','$pathstoredatabase','$alt',$sl)";
            try{
                $connection->exec($queryinsert);
                echo "Insert completed successfully";
            }
            catch(PDOException $e){
                echo $e->getMessage();
            }
        }
    }
    if(isset($_POST['updatephoto'])){
        $photo = $_FILES['photo'];
        $updateid = $_POST['updateid'];
        $tmp_path = $photo['tmp_name'];
        $name = $photo['name'];
        $pathstoredatabase = "images/". rand() . time(). $name;
        $new_path = "../" . $pathstoredatabase;
        $alt = $_POST['alt'];
        if(move_uploaded_file($tmp_path,$new_path)){
            $queryupdate = "UPDATE `sliderphotos` SET `source`='$pathstoredatabase',`alt`='$alt' WHERE sliderphoto_id = $updateid";
            try{
            $connection->exec($queryupdate);
                echo "Update completed successfully";
            }
            catch(PDOException $e){
                echo "Error while updating";
                echo $e->getMessage();
            }
        }
    }
}
$preparedquery->execute();
$result = $preparedquery->fetchAll();
?>
<div class="container">
<h2>Insert a new photo</h2>
<form action="<?php echo $_SERVER['PHP_SELF']. '?sl=' . $sl?>" enctype="multipart/form-data" method="POST">
<div class="row">
<div class="col">
<label>Image</label>
<input name="photo" id="photo" type="file" class="form-control"/>
<label>Alt</label>
<input name="alt" id="alt" class="form-control"/>
<input type="hidden" name="updateid" id="updateid"/>
<input type="submit" name="addphoto" style="margin-top:2rem" class="btn btn-info" value="Add a new photo"></button>
<input type="submit" name="updatephoto" style="margin-top:2rem" class="btn btn-info" value="Update photo"></button>
</form>
</div>
<h2>Slider photos</h2>
        <table class="table table-striped .table-responsive">
            <thead>
                <tr>
                    <th>Photo</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($result as $row): ?>
                <tr>
                    <td><img class="img-fluid" style="max-height:40%" src="<?= '../'.$row->source?>" alt="<?=$row->alt?>"/></td>
                    <td><a data-up="<?= $row->sliderphoto?>" class="update" href="#">Update</a></td>
                    <td><a data-del="<?= $row->sliderphoto?>" class="delete" href="sliderphotos.php?sl=<?=$sl?>&del=<?=$row->sliderphoto?>">Delete</a></td>
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
        url : "ajaxsliderphotos.php",
        type : "POST",
        data : {
            btn : true,
            id : id
        },
        success : function(data){
            var podaci = data.userdata;
            console.log(podaci);
            $("#alt").val(podaci[0].alt);
            $("#updateid").val(podaci[0].sliderphoto_id);
        }
    });
    });
});
</script>
</body>

</html>