<?php
session_start();
if(isset($_SESSION['userName'])){
    $title='categories';
    include 'init.php';

    $do='';
    $do = isset($_GET['do'])? $_GET['do']:'main';
    if($do == 'main'){
        $stmt = $db->prepare("SELECT * FROM `categories`");
        $stmt->execute();
        $cats = $stmt->fetchAll();
        ?>

<section class="text-center">
    <h1 class="mb-2">Manage cats</h1>
    <a class="btn btn-dark mb-2" href="?do=add">Add new Cats+ </a>

    <table class="w-75 m-auto table table-bordered table-hover table-striped table-dark text-white">
        <tr>
            <th>cat id</th>
            <th>cat name</th>
            <th>cat desc</th>
            <th>cat vis</th>
            <th>edit</th>
            <th>delete</th>
        </tr>
        <?php foreach($cats as $cat):
            $id = $cat['id'];
            $name = $cat['name'];
            $desc = $cat['descr'];
            $vis = $cat['visibility'];
        ?>
        <tr>
            <td><?= $id ?></td>
            <td><?= $name ?></td>
            <td><?= $desc ?></td>
            <td><?php if($vis==0){echo 'visible';}else{echo 'invisible';} ?></td>
            <td><a href="?do=edit&id=<?= $id ?>"><i class="fa fa-edit"></i></a></td>
            <td><a href="?do=delete&id=<?= $id ?>"><i class="fa fa-trash"></i></a></td>
        </tr>
        <?php endforeach; ?>
    </table>
</section>

<?php   
    }elseif ($do == 'add'){
        
        $nameErr=$descErr='';
        $name = $desc = '';
        
        if($_SERVER['REQUEST_METHOD']=='POST'){
            //check name
            if(empty($_POST['catName'])){
                $nameErr='name is required';
            }else{
                $name = $_POST['catName'];
            }
            //check desc
            if(empty($_POST['desc'])){
                $descErr='description can not be empty';
            }else{
                $desc = $_POST['desc'];
            }
            //git vis frpm POST
            $vis = $_POST['vis'];

            //check if there's any error or not
            if(empty($nameErr) && empty($descErr)){
                $stmt = $db->prepare("INSERT INTO `categories`(name,descr,visibility)VALUES
                (:catName,:desc,:vis)");

                $checkName = checkDb('name','categories',$name);
                if($checkName>0){
                    redirect('danger','this category is already exists','cats.php?do=add');
                }else{
                    $stmt->bindParam(':catName',$name);
                $stmt->bindParam(':desc',$desc);
                $stmt->bindParam(':vis',$vis);
                $stmt->execute();
                redirect('success','cat added succeccfully','cats.php');
                }
            }
        }
        
        ?>

<form action="" method="post">
    <h1 class="text-center bg-primary text-white">add new category</h1>

    <label for="catName">category name</label>
    <input type="text" name="catName" id="catName">

    <label for="desc">description</label>
    <input type="text" name="desc" id="desc">

    <label>Visible</label>
    <input type="radio" name="vis" id="yes" value="0" checked>
    <label for="yes">yes</label>

    <input type="radio" name="vis" id="no" value="1">
    <label for="yes">no</label>

    <input type="submit" name="addCat" value="add">

</form>

<?php
    }elseif ($do == 'edit'){
        $catId = isset($_GET['id']) && is_numeric($_GET['id'])? intval($_GET['id']):0;
        
        $stmt = $db->prepare("SELECT * FROM `categories` WHERE id=? LIMIT 1");
        $stmt->execute(array($catId));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if($count>0){
            $catName = $row['name'];
            $desc = $row['descr'];
            $vis = $row['visibility'];
        
        
        ?>

<form action="?do=update" method="post">
    <h1>Edit cat</h1>

    <label for="catName">cat name</label>
    <input type="text" name="catName" id="catName" value="<?= $catName ?>">
    <input type="hidden" name="id">

    <label for="desc">cat desc</label>
    <input type="text" name="desc" id="desc" value="<?= $desc ?>">

    <label>Visible</label>

    <input type="radio" name="vis" id="yes" value="0" <?php if($vis==0){echo "checked";} ?>>
    <label for="yes">yes</label>

    <input type="radio" name="vis" id="no" value="1" <?php if($vis==1){echo "checked";} ?>>
    <label for="yes">no</label>

    <input type="submit" name="catEdit" value="edit">

</form>

<?php
        }else{
            redirect('danger','no cat found','cats.php');
        }
    }elseif ($do == 'update'){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $id = $_POST['id'];
            $name = $_POST['catName'];
            $desc = $_POST['desc'];
            $vis = $_POST['vis'];

            $stmt = $db->prepare("UPDATE `categories` SET name=?,descr=?,visibility=?
            WHERE id=?");

            $stmt->execute(array($name,$desc,$vis,$id));
            redirect('success','cat updated','cats.php');
        }else{
            redirect('danger','you cant browser this page', 'index.php');
        }
        
    }elseif ($do == 'delete'){
        $id = $_GET['id'];

        $stmt = $db->prepare("DELETE FROM `categories` WHERE id=:id");
        $stmt->bindParam(':id',$id);
        $stmt->execute();

        redirect('info','cat deleted','cats.php');
    }else{
        echo 'no such this page';
    }

    include $tpl.'footer.php';
    }else{
        header('Location:index.php');
    }