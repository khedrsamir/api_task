<?php
session_start();
if (isset($_SESSION["userName"])) {
    $title = 'users';
    include 'init.php';
    
    $do ='';
    $do = isset($_GET['do']) ? $_GET['do'] : 'main';

    if ($do == 'main') {
        $stmt =$db->prepare("SELECT * FROM `users`");
        $stmt->execute();
        $row = $stmt->fetchAll();
        
        ?>

<h1 class="bg-danger text-center p-4 text-white">Manage users</h1>
<a class="font-weight-bold text-center btn btn-primary text-white d-block m-auto w-25" href="users.php?do=add">Add
    New User
    +</a>
<table class="table table-hover table-striped table-bordered text-center w-75 m-auto">
    <tr>
        <th>ID</th>
        <th>user name</th>
        <th>email</th>
        <th>Full name</th>
        <th>registered date</th>
        <th>type</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    <?php foreach ($row as $x):
        $id = $x['id'];
        $name = $x['name'];
        $email = $x['email'];
        $full_name = $x['full_name'];
        $group_id = $x['group_id'] ==1 ? 'admin':'user';
        $reg_date = $x['reg_date'];
        ?>
    <tr>
        <th><?= $id ?></th>
        <th><?= $name ?></th>
        <th><?= $email ?></th>
        <th><?= $full_name ?></th>
        <th><?= $reg_date ?></th>
        <th><?= $group_id ?></th>
        <th><a href="users.php?do=edit&id=<?= $id ?>"><i class="fa fa-edit text-info"></i></a></th>
        <th><a href="users.php?do=delete&id=<?= $id ?>"><i class="fa fa-trash text-danger"></i></a></th>
    </tr>
    <?php endforeach ?>
</table>


<?php } elseif ($do=='add') { 
    $nameError = $passError = $emailError = $fullError ='';
    $name =$pass=$email=$full='';
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        //check name
        if(empty($_POST['username'])){
            $nameError='name is required';
        }else{
            $name = $_POST['username'];
            if(strlen($name)<6){
                $nameError = 'name should be longer than 6 letters';
            }elseif(strlen($name)>15){
                $nameError = 'name should be less than 15 letters';
            }
        }
        //check password
        if(empty($_POST['password'])){
            $passError='password is required';
        }else{
            $pass = $_POST['password'];
            $hPass = sha1($pass);
        }
        //check email
        if(empty($_POST['email'])){
            $emailError='email is required';
        }else{
            $email = $_POST['email'];
        }
        //check fullName
        if(empty($_POST['full'])){
            $fullError='full Name is required';
        }else{
            $full = $_POST['full'];
        }

        //check if there's any error or not 
        if(empty($nameError) && empty($passError) && empty($emailError) && empty($fullError)){
            //check statement if user name already exists
            $checkUserName = checkDb('name','users',$name);
            if($checkUserName>0){
                redirect('danger', 'sorry this user already exists','users.php?do=add');
            }else{
                $stmt =$db->prepare("INSERT INTO 
        `users`(name,password,email,full_name,reg_date)
        VALUES(:username,:password,:email,:full,now())");

        $stmt->bindParam(':username', $name);
        $stmt->bindParam(':password', $hPass);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':full', $full);

        $stmt->execute();
        
    
        redirect('success','user added successfully','users.php',2);
            }
        }  
    }
    
    ?>

<form id="editForm" action="" method="POST">
    <h1>Add new member</h1>
    <label for="username">username</label>
    <input type="text" name="username" id="username" value="<?= $name?>">
    <span class="text-danger">
        <? $nameError?>
    </span>

    <label for="password">password</label>
    <input type="password" name="password" id="password">
    <span class="text-danger">
        <? $passError?>
    </span>

    <label for="email">email</label>
    <input type="email" name="email" id="email" value="<?= $email?>">
    <span class="text-danger">
        <? $emailError?>
    </span>

    <label for="full"> Full name</label>
    <input type="text" name="full" id="full" value="<?= $full?>">
    <span class="text-danger">
        <? $fullError?>
    </span>

    <input type="submit" name="add" value="add user">
</form>


<?php }

elseif($do=='edit'){
$userId=isset($_GET["id"]) && is_numeric($_GET["id"]) ? intval($_GET["id"]) : 0; $stmt=$db->
prepare("SELECT * FROM `users` WHERE id=? LIMIT 1");
$stmt->execute(array($userId));
$row = $stmt->fetch();
$count = $stmt->rowCount();


$username = $row["name"];
$email = $row["email"];
$fullName = $row["full_name"];

$password = $row['password'];

if ($count > 0) {?>
<form id="editForm" action="?do=update" method="POST">
    <h1>Edit Member</h1>

    <label for="username">User name</label>
    <input type="text" name="username" id="username" value="<?= $username ?>">

    <input type="hidden" name="id" value="<?= $userId ?>">

    <label for="oldPassword">Old Password</label>
    <input type="hidden" name="oldPassword" value="<?= $password ?>">
    <input type="password" name="newPassword" id="oldPassword">

    <label for="email">Email</label>
    <input type="email" name="email" id="email" value="<?= $email ?>">

    <label for="full">Full name</label>
    <input type="text" name="full" id="full" value="<?= $fullName ?>">
    <input type="submit" name="edit" value="Edit">
</form>
<?php
        } else {
            echo "Sorry, no user found.";
        }
    } elseif ($do == "update") {
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $id = $_POST['id'];
            $name = $_POST['username'];
            $email = $_POST['email'];
            $full = $_POST['full'];

            $pass ='';
            if(empty($_POST['newPassword'])){
                $pass = $_POST['oldPassword'];
            }else{
                $pass = $_POST['newPassword'];
            }
            $hPass = sha1($pass);

            $stmt =$db->prepare("UPDATE users SET name =?,password=?,email=?, full_name=? WHERE id=?");
            $stmt->execute(array($name,$hPass,$email,$full,$id));

            redirect('success','user updated','users.php',3);
            //echo"<h1 class='alert alert-success'> user updated</h1>";
        }else{
            redirect('danger','you cant browse this page','index.php',0);
            //echo "you cant browse this page ";
        }
    }elseif($do='delete'){
        $id = $_GET['id'];

        if($id==1){
            echo"<h2 class='alert alert-danger'> you cant delete this user</h2>";
        }else{
            $stmt =$db->prepare("DELETE FROM `users` WHERE id=:id");
            $stmt->bindParam(':id',$id);
            $stmt->execute();

            redirect('info','User deleted successfully','users.php',2);
            //echo"<h2 class='alert alert-success'>User deleted successfully</h2>";
        }
    }
     else {
        echo"there no such this page";
    }
        
include $tpl . 'footer.php';
} else {
header('Location: index.php');
}
?>