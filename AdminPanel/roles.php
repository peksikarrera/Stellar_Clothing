<?php include "head.php";
$query = "SELECT * FROM roles";
if(isset($_GET['del'])){
    $delid = $_GET['del'];
    $querydel = "DELETE FROM roles WHERE role_id = $delid";
    try{
        $connection->exec($querydel);
        echo "Deletion successfully completed";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
}
if(isset($_POST['addrole'])){
    $rolename = $_POST['rolename'];
    $insertrole = "INSERT INTO roles VALUES('','$rolename')";
    try{
    $result = $connection->exec($insertrole);
    echo "A new role is added";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Error while inserting a new role";
    }
}
if(isset($_POST['updaterole'])){
    $rolename = $_POST['rolename'];
    $updateroleid = $_POST['updateid'];
    $updatequery = "UPDATE roles SET rolename = '$rolename' WHERE role_id = $updateroleid";
    try{
    $connection->exec($updatequery);
    echo "The role is updated successfully";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Error occured during the update of a role";
    }
}
$result = $connection->query($query)->fetchAll();
?>
<div class="container">
<h2>Add a new role</h2>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
<div class="row">
<div class="col">
<label>Role name</label>
<input name="rolename" id="rolename" class="form-control"/>
<input type="hidden" name="updateid" id="updateid"/>
<input type="submit" name="addrole" style="margin-top:2rem" class="btn btn-info" value="Add a new role"></button>
<input type="submit" name="updaterole" style="margin-top:2rem" class="btn btn-info" value="Update role"></button>
</form>
</div>
<h2>Roles</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($result as $row): ?>
                <tr>
                    <td><?= $row->rolename?></td>
                    <td><a href="#" class="update" data-up="<?= $row->role_id ?>" data-del="<?= $row->role_id ?>">Update</a></td>
                    <td><a href="roles.php?del=<?=$row->role_id?>" class="delete" data-up="<?= $row->role_id ?>" data-del="<?= $row->role_id ?>">Delete</a></td>
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
        url : "ajaxrolefetch.php",
        type : "POST",
        data : {
            btn : true,
            id : id
        },
        success : function(data){
            var podaci = data.userdata;
            console.log(podaci);
            $("#rolename").val(podaci[0].rolename);
            $("#updateid").val(podaci[0].role_id);
        }
    });
    });
});
</script>
</body>

</html>