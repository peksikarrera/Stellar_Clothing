<?php include "head.php";
$query = "SELECT * FROM poll";
if(isset($_GET['del'])){
    $delid = $_GET['del'];
    $querydel = "DELETE FROM poll_users_options WHERE poll_id = $delid";
    try{
        $connection->exec($querydel);
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
    $delid = $_GET['del'];
    $querydel = "DELETE FROM options WHERE poll_id = $delid";
    try{
        $connection->exec($querydel);
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
    $querydel = "DELETE FROM poll WHERE poll_id = $delid";
    try{
        $connection->exec($querydel);
        echo "Deletion successfully completed";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
}
if(isset($_POST['addpoll'])){
    $question =  $_POST['question'];
    $options = $_POST['options'];
    if(empty($_POST['optradio'])) $active = 0 ; else $active = (int)$_POST['optradio'];
    $queryinsert1 = "INSERT INTO `poll`(`poll_id`, `question`, `active`) VALUES ('','$question',$active)";
    try{
    $connection->exec($queryinsert1);
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Insert failed";
    }
    $id = $connection->lastinsertid();
        foreach($options as $option){
        $queryinsert2 = "INSERT INTO `options`(`option_id`, `name`, `poll_id`) VALUES ('','$option',$id)";
            try{
            $connection->exec($queryinsert2);
            }
            catch(PDOException $e){
                echo $e->getMessage();
                echo "Insert failed";
            }
        }
        echo "Insert successfully completed";
    }
if(isset($_POST['updatepoll'])){
    $options = $_POST['options'];
    $question = $_POST['question'];
    if(empty($_POST['optradio'])) $active = 0 ; else $active = (int)$_POST['optradio'];
    $updateid = $_POST['updateid'];
    $queryupdate1 = "UPDATE `poll` SET question='$question',`active`= $active WHERE poll_id = $updateid";
    try{
        $connection->exec($queryupdate1);
    }
    catch(PDOException $e){
        $e->getMessage();
        echo "Update failed";
    }
    $fetchidsquery = "SELECT option_id FROM options WHERE poll_id = $updateid";
    $fetchidsresult = $connection->query($fetchidsquery)->fetchAll(PDO::FETCH_ASSOC);
    $counter = 0;
    foreach($options as $option){
        $optarr = $fetchidsresult[$counter];
        $optid = (int)$optarr["option_id"];
        $queryupdate2 = "UPDATE options SET name='$option', poll_id = $updateid WHERE option_id = $optid";
        $counter++;
        try{
            $connection->exec($queryupdate2);
        }
        catch(PDOException $e){
            echo $e->getMessage();
            echo "Update failed";
        }
    }
    echo "Update completed successfully";
}
$result = $connection->query($query)->fetchAll();
?>
<div class="container">
<h2>Add a poll</h2>
<form action="<?php echo $_SERVER['PHP_SELF']?>" onSubmit = "return validate()" method="POST">
<div class="row">
<div class="col">
<label>Question</label>
<input name="question" id="question" class="form-control"/>
<label id="nopt">Number of options</label>
<select class="form-control" id="nooptions">
    <option value="5">5</option>
    <option value="4">4</option>
    <option value="3">3</option>
    <option value="2">2</option>
    <option value="1">1</option>
</select>
<span id="options"></span>
<label for="chbactive">Active</label>
<label class="radio-inline"><input type="radio" value="1" id="yes" name="optradio">Yes</label>
<label class="radio-inline"><input type="radio" value="0" id="no" name="optradio">No</label>
<div class="alert alert-info">
Kada dodajete novu anketu ili zelite da stara bude aktuelna morate da obelezite za active : yes, dok za trenutno aktuelnu anketu mora da bude obelezeno active : no. Podrazumeva se da ste za sve ostale neaktuelne ankete ranije stavljali active : no
</div>
</div>
<input type="hidden" name="updateid" id="updateid"/>
<input type="submit" name="addpoll" id="addpoll" style="margin-top:2rem" class="btn btn-info" value="Add a new poll"></button>
<input type="submit" name="updatepoll" id="updatepoll" style="margin-top:2rem" class="btn btn-info" value="Update poll"></button>
</form>
</div>
<h2>Poll</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Active</th>
                    <th>Votes</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($result as $row): ?>
                <tr>
                    <td><?= $row->question?></td>
                    <td><?= $row->active ? "Yes" : "No" ?></td>
                    <td><a href="<?php echo 'polloptions.php?poll='.$row->poll_id?>">Options</a></td>
                    <td><a href="#" class="update" data-up="<?= $row->poll_id?>">Update</a></td>
                    <td><a href="poll.php?del=<?=$row->poll_id?>" class="delete" data-del="<?= $row->poll_id?>">Delete</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
$("#nooptions").on("change",function(){
    var valueselected = $("#nooptions").val();
    var output = "";
    for(var i=1; i<=valueselected;i++){
        output += '<label>Option</label> ' + i +'<input name="options[]" class="form-control options"/>';
    }
    $("#options").html(output);
});
function validate(){
    var question = $("#question").val();
    var options = $(".name").val();
    if(question == "" || options == ""){
        console.log("if working");
        return false;
    }
    else{
        console.log("else working");
    }
}
    $(".update").on("click",function(e){
    var id = $(this).data("up");
    console.log(id);
    $.ajax({
        url : "ajaxpollfetch.php",
        type : "POST",
        data : {
            btn : true,
            id : id
        },
        dataType : "JSON",
        success : function(data){
            var podaci = data.userdata;
            console.log(podaci);
            var ispis = "";
            for(var i=0; i<podaci.length;i++){
                ispis += "<label> Option "+ (i+1) + " </label>" + '<input name="options[]" value="'+ podaci[i].name + '" class="form-control options"/>';
            }
            $("#nooptions").hide();
            $("#nopt").hide();
            $("#updateid").val(podaci[0].poll_id);
            $("#question").val(podaci[0].question);
            $("#options").html(ispis);
            if(podaci[0].active == "1"){
                $('#yes').prop('checked', true);
            }
            else{
                $('#no').prop('checked', true);
            }
        }
    });
});
</script>
</script>
</body>

</html>