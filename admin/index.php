<?php
// start session
session_start();
//set title variable
$title = 'login';
// no navbar variables
$noNav= '';
if(isset($_SESSION['userName'])){
    header('Location:dashboard.php');
}
//include initilalize file
include 'init.php';


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $userName = $_POST['UserName'];
    $password = $_POST['password'];
    $hPass    = sha1($password);

    //echo 'normal pass is'.$password .'    hash pass is '.$hPass;
    $stmt = $db->prepare("SELECT id,name,password FROM `users` WHERE name= ? AND password=? AND group_id=1");
    $stmt->execute(array($userName,$hPass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();

    if($count>0){
        $_SESSION['userName'] = $userName;
        $_SESSION["id"] = $row["id"];
        header('Location:dashboard.php');
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

<?php
//include header
 include $tpl.'footer.php' 
 ?>