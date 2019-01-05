<?php include "head.php";
$query = "SELECT * FROM ((users u INNER JOIN roles r ON r.role_id = u.role_id) INNER JOIN usercarddata ucd ON ucd.user_id = u.user_id) INNER JOIN personaluserdata pud ON pud.user_id = u.user_id";
if(isset($_GET['del'])){
    $delid = $_GET['del'];
    $querydel = "DELETE FROM personaluserdata WHERE user_id = $delid";
    try{
        $connection->exec($querydel);
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
    $querydel = "DELETE FROM usercarddata WHERE user_id = $delid";
    try{
        $connection->exec($querydel);
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
    $querydel = "DELETE FROM users WHERE user_id = $delid";
    try{
        $connection->exec($querydel);
        echo "Delete succeeded";
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
}
if(isset($_POST['adduser'])){
    $roleid = $_POST['roles'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $phonenumber = $_POST['phonenumber'];
    $homeaddress = $_POST['homeaddress'];
    $zipcode = $_POST['zipcode'];
    $country = $_POST['country'];
    $day = $_POST['day'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $datestring = $year."-".$month."-".$day;
    $timestamp = strtotime($datestring);
    $datetime = date("Y-m-d H:i:s");
    $date = date("Y-m-d H:i:s",$timestamp);
    $cardnumber = $_POST['cardnumber'];
    $csc = $_POST['csc'];
    $queryinsertusers = "INSERT INTO users(`user_id`,`firstname`,`lastname`,`email`,`password`,`phonenumber`,`time`,`active`,`random`,`nooftrials`,`banned`,`role_id`) VALUES('',:firstname,:lastname,:email,:password,:phonenumber,:datetime,1,null,0,0,:roleid)";
    $prepareinsertquery = $connection->prepare($queryinsertusers);
    $prepareinsertquery->bindParam(":firstname",$firstname);
    $prepareinsertquery->bindParam(":lastname",$lastname);
    $prepareinsertquery->bindParam(":email",$email);
    $prepareinsertquery->bindParam(":password",$password);
    $prepareinsertquery->bindParam(":datetime",$datetime);
    $prepareinsertquery->bindParam(":phonenumber",$phonenumber);
    $prepareinsertquery->bindParam(":roleid",$roleid);
    try{
        $prepareinsertquery->execute();
    }
    catch(PDOException $e){
        echo "You have an error";
        echo $e->getMessage();
    }
    $queryinsertcard = "INSERT INTO usercarddata VALUES('',:cardnumber,:csc,(SELECT user_id FROM users WHERE email='$email'))";
    $preparedquerycard = $connection->prepare($queryinsertcard);
    $preparedquerycard->bindParam(":cardnumber",$cardnumber);
    $preparedquerycard->bindParam(":csc",$csc);
    try{
        $preparedquerycard->execute();
    }
    catch(PDOException $e){
        echo "You have an error";
        echo $e->getMessage();
    }
    $queryinsertpersonal = "INSERT INTO personaluserdata VALUES('',:homeaddress,:zipcode,:country,:date,(SELECT user_id FROM users WHERE email='$email'))";
    $preparedquerypersonal = $connection->prepare($queryinsertpersonal);
    $preparedquerypersonal->bindParam(":homeaddress",$homeaddress);
    $preparedquerypersonal->bindParam(":zipcode",$zipcode);
    $preparedquerypersonal->bindParam(":country",$country);
    $preparedquerypersonal->bindParam(":date",$date);
    try{
        $preparedquerypersonal->execute();
        echo "You 've added a new user";
    }
    catch(PDOException $e){
        echo "You have an error";
        echo $e->getMessage();
    }
}
if(isset($_POST['updateuser'])){
    $roleid = $_POST['roles'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $phonenumber = $_POST['phonenumber'];
    $homeaddress = $_POST['homeaddress'];
    $zipcode = $_POST['zipcode'];
    $country = $_POST['country'];
    $day = $_POST['day'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $datestring = $year."-".$month."-".$day;
    $timestamp = strtotime($datestring);
    $datetime = date("Y-m-d H:i:s");
    $date = date("Y-m-d H:i:s",$timestamp);
    $cardnumber = $_POST['cardnumber'];
    $csc = $_POST['csc'];
    $updateid = $_POST['idUserUpdate'];
    try{
        $updateusertable = "UPDATE `users` SET `firstname`=:firstname,`lastname`=:lastname,`email`=:email,`password`=:password,`phonenumber`=:phonenumber,`time`=:datetime,`role_id`=:roleid WHERE user_id = :updateid";
        $prepareusertable = $connection->prepare($updateusertable);
        $prepareusertable->bindParam(":updateid",$updateid);
        $prepareusertable->bindParam(":firstname",$firstname);
        $prepareusertable->bindParam(":lastname",$lastname);
        $prepareusertable->bindParam(":email",$email);
        $prepareusertable->bindParam(":password",$password);
        $prepareusertable->bindParam(":phonenumber",$phonenumber);
        $prepareusertable->bindParam(":datetime",$datetime);
        $prepareusertable->bindParam(":roleid",$roleid);
        $prepareusertable->execute();
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }
    try{
        $updatepersonaltable = "UPDATE `personaluserdata` SET `homeaddress`=:homeaddress,`zipcode`=:zipcode,`country`=:country,`birth`=:birth WHERE user_id = :userid";
        $prepareduserpersonal = $connection->prepare($updatepersonaltable);
        $prepareduserpersonal->bindParam(":homeaddress",$homeaddress);
        $prepareduserpersonal->bindParam(":zipcode",$zipcode);
        $prepareduserpersonal->bindParam(":country",$country);
        $prepareduserpersonal->bindParam(":birth",$date);
        $prepareduserpersonal->bindParam(":userid",$updateid);
        $prepareduserpersonal->execute();
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }
    try{
        $updatecardtable = "UPDATE `usercarddata` SET `cardnumber`=:cardnumber,`csc`=:csc WHERE user_id = :userid";
        $preparedupdatecard = $connection->prepare($updatecardtable);
        $preparedupdatecard->bindParam(":cardnumber",$cardnumber);
        $preparedupdatecard->bindParam(":csc",$csc);
        $preparedupdatecard->bindParam(":userid",$updateid);
        $preparedupdatecard->execute();
        echo "Update completed successfully";
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }
}
$result = $connection->query($query)->fetchAll();
?>
<script src="js/scriptupdateuser.js">
</script>
<div class="container">
<h2>Insert a user</h2>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
<div class="row">
<div class="col">
<label>Role</label>
<?php
    $queryroles = "SELECT * FROM roles";
    $resultroles = $connection->query($queryroles)->fetchAll();
    echo '<select class="form-control" id="roles" name="roles">';
    foreach($resultroles as $role){
        echo '<option value="'. $role->role_id . '">' . $role->rolename . '</option>'; 
    }
    echo "</select>";
?>
<label>First name</label>
<input name="firstname" id="firstname" class="form-control"/>
<label>Last name</label>
<input name="lastname" id="lastname" class="form-control"/>
<label>E-mail</label>
<input name="email" id="email" class="form-control"/>
<label>Password</label>
<input name="password" id="password" type="password" class="form-control"/>
<label>Phone number</label>
<input name="phonenumber"  id="phone" class="form-control"/>
<label>Home address</label>
<input name="homeaddress" id="home" class="form-control"/>
<label>Zip code</label>
<input name="zipcode" id="zip" class="form-control"/>
<label>Country</label>
<input name="country" id="country" class="form-control"/>
<input type="hidden" name="idUserUpdate" id="idUserUpdate"/>
<div class="row">
    <div class="col-sm-4">
    <label>Day</label>
    <select class="form-control" name="day">
        <?php for($i=31;$i>=1;$i--): ?>
        <option value="<?=$i?>"><?=$i?></option>
        <?php endfor; ?>
    </select>
    </div>
    <div class="col-sm-4">
    <label>Month</label>
    <select class="form-control" name="month">
            <?php for($i=12;$i>=1;$i--): ?>
            <option value="<?=$i?>"><?=$i?></option>
            <?php endfor; ?>
    </select>
    </div>
    <div class="col-sm-4">
    <label>Year</label>
    <select class="form-control" name="year">
        <?php for($i=2018;$i>=1930;$i--): ?>
        <option value="<?=$i?>"><?=$i?></option>
        <?php endfor; ?>
    </select>
    </div>
</div>
<label>Payment card number</label>
<input name="cardnumber" id="cardnumber" class="form-control"/>
<label>Security card number</label>
<input name="csc" id="csc" class="form-control"/>
<input type="submit" name="adduser" style="margin-top:2rem" class="btn btn-info" value="Add a new user"></button>
<input type="submit" name="updateuser" style="margin-top:2rem" class="btn btn-info" value="Update user"></button>
</form>
</div>
</div>
<h2>Users</h2>
<div class="row">
<div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Role</th>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Email</th>
                    <th>Phone number</th>
                    <th>Registered on</th>
                    <th>Active</th>
                    <th>Banned</th>
                    <th>Home address</th>
                    <th>Zip code</th>
                    <th>Country</th>
                    <th>Date of birth</th>
                    <th>Card number</th>
                    <th>Security card number</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($result as $row): ?>
                <tr>
                    <td><?= $row->user_id ?></td>
                    <td><?= $row->rolename?></td>
                    <td><?= $row->firstname?></td>
                    <td><?= $row->lastname?></td>
                    <td><?= $row->email?></td>
                    <td><?= $row->phonenumber?></td>
                    <td><?= $row->time?></td>
                    <td><?php echo $row->active == 1 ? "Yes" : "No" ?></td>
                    <td><?php echo $row->banned == 1 ? "Yes" : "No" ?></td>
                    <td><?= $row->homeaddress ?> </td>
                    <td><?= $row->zipcode ?></td>
                    <td><?= $row->country ?></td>
                    <td><?= $row->birth ?></td>
                    <td><?= $row->cardnumber ?></td>
                    <td><?= $row->csc ?></td>
                    <td><a href="#" class="update" data-up="<?= $row->user_id?>">Update</a></td>
                    <td><a href="users.php?del=<?=$row->user_id?>" class="delete" data-del="<?= $row->user_id?>">Delete</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
</div>
</div>
</body>
</html>