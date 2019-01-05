<?php include "head.php";
$query = "SELECT * FROM headingmenu";
if(isset($_GET['del'])){
    $delid = $_GET['del'];
    $querydel = "DELETE FROM headingmenu WHERE item_id = $delid";
    try{
        $connection->exec($querydel);
        echo "Deletion successfully completed";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
}
if(isset($_POST['addheaderitem'])){
    $link = $_POST['link'];
    $level = $_POST['level'];
    $parent = $_POST['parent'];
    $name = $_POST['name'];
    $colnum = $_POST['colnum'];
    $insertheadingitem = "INSERT INTO `headingmenu`(`item_id`, `link`, `name`, `level`, `parent`, `item_column`) VALUES ('','$link','$name',$level,$parent,$colnum)";
    try{
    $connection->exec($insertheadingitem);
    echo "Insert successfully completed";
    }
    catch(PDOException $e){
        echo "Insert didn't work. Try again";
        echo $e->getMessage();
    }
}
if(isset($_POST['updateheaderitem'])){
    $link = $_POST['link'];
    $level = $_POST['level'];
    $parent = $_POST['parent'];
    $name = $_POST['name'];
    $colnum = $_POST['colnum'];
    $itemid = $_POST['updateid'];
    $updateheadingitem = "UPDATE `headingmenu` SET `link`= '$link',`name`='$name',`level`= $level,`parent`= $parent,`item_column`= $colnum WHERE item_id = $itemid";
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
<h2>Add a new item to the header menu</h2>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
<div class="row">
<div class="col">
<label>Link</label>
<input name="link" id="link" class="form-control"/>
<label>Name</label>
<input name="name" id="name" class="form-control"/>
<label>Level</label>
<input name="level" id="level" class="form-control"/>
<label>Parent</label>
<input name="parent" id="parent" class="form-control"/>
<label>Column number</label>
<input name="colnum" id="colnum" class="form-control"/>
<input type="hidden" name="updateid" id="updateid"/>
<input type="submit" name="addheaderitem" style="margin-top:2rem" class="btn btn-info" value="Add a new header item"></button>
<input type="submit" name="updateheaderitem" style="margin-top:2rem" class="btn btn-info" value="Update header item"></button>
</form>
</div>
<h2>Heading menu</h2>
<div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Link</th>
                    <th>Name</th>
                    <th>Level</th>
                    <th>Parent</th>
                    <th>Column number</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($result as $row): ?>
                <tr>
                    <td><?= $row->item_id?></td>
                    <td><?= $row->link?></td>
                    <td><?= $row->name?></td>
                    <td><?= $row->level?></td>
                    <td><?= $row->parent?></td>
                    <td><?= $row->item_column?></td>
                    <td><a href="#" class="update" data-up="<?= $row->item_id?>" data-del="<?= $row->item_id?>">Update</a></td>
                    <td><a href="headingmenu.php?del=<?=$row->item_id?>" class="delete" data-up="<?= $row->item_id?>" data-del="<?= $row->item_id?>">Delete</a></td>
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
        url : "ajaxheadingmenufetch.php",
        type : "POST",
        data : {
            btn : true,
            id : id
        },
        success : function(data){
            var podaci = data.userdata;
            console.log(podaci);
            $("#link").val(podaci[0].link);
            $("#level").val(podaci[0].level);
            $("#name").val(podaci[0].name);
            $("#parent").val(podaci[0].parent);
            $("#colnum").val(podaci[0].item_column);
            $("#updateid").val(podaci[0].item_id);
        }
    });
    });
});
</script>
</body>

</html>