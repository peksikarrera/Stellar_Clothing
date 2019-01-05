<?php
include "head.php";
if(isset($_GET['del'])){
    $delid = $_GET['del'];
    $querydel = "DELETE FROM poll_users_options WHERE option_id = $delid";
    try{
        $connection->exec($querydel);
    }
    catch(PDOException $e){
        echo $e->getMessage();
        echo "Deletion failed";
    }
    $querydel = "DELETE FROM options WHERE option_id = $delid";
        try{
            $connection->exec($querydel);
            echo "Deletion successfully completed";
        }
        catch(PDOException $e){
            echo $e->getMessage();
            echo "Deletion failed";
        }
}
if(isset($_GET['poll'])){
    $poll = $_GET['poll'];
    $query = "SELECT * FROM poll p INNER JOIN options o ON o.poll_id = p.poll_id WHERE p.poll_id = $poll GROUP BY o.option_id";
    $result = $connection->query($query)->fetchAll();
}
?>
<div class="container">
<h2>Poll options</h2>
<p>Administrator is only allowed to review votes and to edit options.</p>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Option name</th>
                    <th>Number of votes</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($result as $row): ?>
                <tr>
                    <td><?= $row->name?></td>
                    <?php 
                        $querynumbervotes = "SELECT COUNT(*) as votesnumber FROM poll_users_options WHERE poll_id = $row->poll_id AND option_id = $row->option_id GROUP BY option_id";
                        $result = $connection->query($querynumbervotes)->fetch();
                    ?>
                    <td><?php if(empty($result->votesnumber)) echo "0" ; else echo $result->votesnumber; ?></td>
                    <td><a href="polloptions.php?del=<?= $row->option_id?>&poll=<?=$poll?>">Delete</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
<?php $query = "SELECT * FROM (users u INNER JOIN poll_users_options puo ON puo.user_id = u.user_id) INNER JOIN options o ON o.poll_id = puo.poll_id WHERE puo.poll_id = $poll GROUP BY u.user_id"; 
$result = $connection->query($query)->fetchAll();
?>
<h2>Users who voted</h2>
<table class="table table-striped">
            <thead>
                <tr>
                <th>ID</th>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Email</th>
                <th>Voted for option</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($result as $row): ?>
                <tr>
                <td><?= $row->user_id ?></td>
                    <td><?= $row->firstname?></td>
                    <td><?= $row->lastname?></td>
                    <td><?= $row->email?></td>
                    <td><?= $row->name?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
</div>
</body>

</html>