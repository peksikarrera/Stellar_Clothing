<?php include "head.php";
$query = "SELECT * FROM slider s";
if(isset($_GET['del'])){
    $delid = $_GET['del'];
    $querydel = "DELETE FROM slider WHERE slider_id = $delid";
    try{
        $connection->exec($querydel);
        echo "Deletion successfully completed";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
}
if(isset($_POST['addslider'])){
    $slider = $_POST['slider'];
    if(empty($_POST['optradio'])) $active = 0 ; else $active = $_POST['optradio'];
    $insertquery = "INSERT INTO slider VALUES('',$active,'$slider')";
    try{
        $connection->exec($insertquery);
        echo "Insert completed successfully";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Error while inserting the new slider";
    }
}
if(isset($_POST['updateslider'])){
    $slider = $_POST['slider'];
    $active = (int)$_POST['optradio'];
    $id = $_POST['updateid'];
    $insertquery = "UPDATE slider SET title = '$slider', active = $active WHERE slider_id = $id";
    try{
        $connection->exec($insertquery);
        echo "Update completed successfully";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Error while updating the slider";
    }
}
$result = $connection->query($query)->fetchAll();
?>
<div class="container">
<h2>Add a slider</h2>
<p>After adding a new slider, you must add photos to your slider. Otherwise, it won't work properly.</p>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
<div class="row">
<div class="col">
<label>Slider</label>
<input name="slider" id="slider" class="form-control"/>
<label for"chbactive">Active</label>
<label class="radio-inline"><input type="radio" value="1" id="yes" name="optradio">Yes</label>
<label class="radio-inline"><input type="radio" value="0" id="no" name="optradio">No</label>
<div class="alert alert-info">
Kada dodajete novi slajder ili zelite da stari bude aktuelan morate da obelezite za active : yes, dok za trenutno aktuelan slajder mora da bude obelezeno active : no. Podrazumeva se da ste za sve ostale neaktuelne slajdere ranije stavljali active : no
</div>
</div>
<input type="hidden" name="updateid" id="updateid"/>
<input type="submit" name="addslider" style="margin-top:2rem" class="btn btn-info" value="Add a new slider"></button>
<input type="submit" name="updateslider" style="margin-top:2rem" class="btn btn-info" value="Update slider"></button>
</form>
</div>
<h2>Slider</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>title</th>
                    <th>Active</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($result as $row): ?>
                <tr>
                    <td><?= $row->title?></td>
                    <?php if ($row->active == 1) echo '<td>Yes</td>'; else echo '<td>No</td>'; ?>
                    <td><a href="sliderphotos.php?sl=<?=$row->slider_id?>">Photos</a></td>
                    <td><a href="#" data-up="<?= $row->slider_id?>" class="update">Update</a></td>
                    <td><a href="slider.php?del=<?=$row->slider_id?>" data-del="<?= $row->slider_id?>" class="delete">Delete</a></td>
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
        url : "ajaxsliderfetch.php",
        type : "POST",
        data : {
            btn : true,
            id : id
        },
        success : function(data){
            var podaci = data.userdata;
            console.log(podaci);
            $("#slider").val(podaci[0].title);
            if(podaci[0].active == "1"){
                $('#yes').prop('checked', true);
            }
            else{
                $('#no').prop('checked', true);
            }
            $("#updateid").val(podaci[0].slider_id);
        }
    });
    });
});
</script>
</body>

</html>