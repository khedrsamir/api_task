<?php
session_start();
$title = 'login';
if(isset($_SESSION['user'])){
    header('Location:index.php');
}
include 'init.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $user = $_POST['UserName'];
    $password = $_POST['password'];
    $hPass    = sha1($password);

    $stmt = $db->prepare("SELECT id,name,password FROM `users` WHERE name= ? AND password=?");
    $stmt->execute(array($user,$hPass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();

    if($count>0){
        $_SESSION['userName'] = $user;
        $_SESSION["id"] = $row["id"];
        header('Location:index.php');
    }
}
?>

<!-- start of admin login section -->
<section class="container" id="login">
    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" id="adminForm" method="">
        <h3>Admin Login</h3>
        <input type="text" name="UserName" id="UserName" placeholder="enter user name">
        <input type="password" name="password" id="password" placeholder="enter password">
        <input type="submit" name="login" value="login">
        <!-- end of admin login section -->
    </form>

</section>

<?php include $tpl.'footer.php'; ?>