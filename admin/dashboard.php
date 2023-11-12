<?php 
session_start();

if (isset($_SESSION['userName'])){
    //cottent her
    $title = 'dashboard';
    include 'init.php';
    
    $stmt = $db->prepare("SELECT id FROM users");
    $stmt->execute();
    $userCount = $stmt->rowCount();

    ?>

<section id="dashboard">
    <div class="container">
        <h1 class="text-center my-5 text-secondary p-3">Dashboard</h1>
        <div class="row">

            <div class="col-3">
                <div class="bg-primary text-center text-white p-3 rounded">
                    <p>Total Member</p>
                    <span class="timer display-3" data-from="0" data-to="<?= $userCount?>" data-speed="2000"></span>
                </div>
            </div>

            <div class="col-3">
                <div class="bg-success text-center text-white p-3 rounded">
                    <p>pending Member</p>
                    <span class="timer display-3" data-from="0" data-to="56" data-speed="2000"></span>
                </div>
            </div>
            <div class="col-3">
                <div class="bg-danger text-center text-white p-3 rounded">
                    <p>total prouducts</p>
                    <span class="display-3">1000</span>
                </div>
            </div>

            <div class="col-3">
                <div class="bg-warning text-center text-white p-3 rounded">
                    <p>Total comment</p>
                    <span class="display-3">20000</span>
                </div>
            </div>

        </div><!-- row -->
    </div><!-- container -->
</section>

<?php include $tpl.'footer.php';
}else{
    header('Location:index.php');
}