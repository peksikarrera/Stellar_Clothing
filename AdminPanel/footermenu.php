<?php include "head.php";
$query = "SELECT * FROM footermenu";
if(isset($_GET['del'])){
    $delid = $_GET['del'];
    $querydel = "DELETE FROM footermenu WHERE footer_id = $delid";
    try{
        $connection->exec($querydel);
        echo "Deletion successfully completed";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
}
if(isset($_POST['addfooteritem'])){
    $link = $_POST['link'];
    $name = $_POST['name'];
    $insertfooteritem = "INSERT INTO `footermenu`(`footer_id`, `link`, `name`) VALUES ('','$link','$name')";
    try{
    $connection->exec($insertfooteritem);
    echo "Insert successfully completed";
    }
    catch(PDOException $e){
        echo "Insert didn't work. Try again";
        echo $e->getMessage();
    }
}
if(isset($_POST['updatefooteritem'])){
    $link = $_POST['link'];
    $name = $_POST['name'];
    $itemid = $_POST['updateid'];
    $updateheadingitem = "UPDATE `footermenu` SET `link`= '$link',`name`='$name' WHERE footer_id = $itemid";
    try{
    $connection->exec($updateheadingitem);
    echo "Update successfully completed";
    }
    catch(PDOException $e){
        echo "Update didn't work. Try again";
        echo $e->getMessage();
    }
}
$result = $connection->query($query)->fetchAll();
?>
<div class="container">
<h2>Add a new item to the footer menu</h2>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
<div class="row">
<div class="col">
<label>Link</label>
<input name="link" id="link" class="form-control"/>
<label>Name</label>
<input name="name" id="name" class="form-control"/>
<input type="hidden" name="updateid" id="updateid"/>
<input type="submit" name="addfooteritem" style="margin-top:2rem" class="btn btn-info" value="Add a new footer item"></button>
<input type="submit" name="updatefooteritem" style="margin-top:2rem" class="btn btn-info" value="Update footer item"></button>
</form>
</div>
<h2>Footer menu</h2>
<div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Link</th>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($result as $row): ?>
                <tr>
                    <td><?= $row->link?></td>
                    <td><?= $row->name?></td>
                    <td><a href="#" class="update" data-up="<?= $row->footer_id?>">Update</a></td>
                    <td><a href="footermenu.php?del=<?=$row->footer_id?>" class="delete" data-del="<?= $row->footer_id?>">Delete</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
    <script>
    $(document).ready(function(){
    $(".update").on("click",function(e){
    var id = $(this).data("up");
    console.log(id);
    $.ajax({
        url : "ajaxfootermenufetch.php",
        type : "POST",
        data : {
            btn : true,
            id : id
        },
        success : function(data){
            var podaci = data.userdata;
            console.log(podaci);
            $("#link").val(podaci[0].link);
            $("#name").val(podaci[0].name);
            $("#updateid").val(id);
        }
    });
    });
});
</script>
</body>

</html>