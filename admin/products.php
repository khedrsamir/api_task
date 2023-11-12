<?php
session_start();
if(isset($_SESSION['userName'])){
    $title = 'products';
    include 'init.php';
    
    $do = isset($_GET['do'])?$_GET['do']:'main';

    if ($do=='main'){
        $stmt =$db->prepare("SELECT products.* ,
        categories.name As cat_name
        ,users.name AS user_name
        FROM products
        INNER JOIN categories ON categories.id =products.cat_id
        INNER JOIN users ON users.id =products.user_id
        ");
        $stmt->execute();
        $row = $stmt->fetchAll();
        ?>

<h1 class="bg-danger text-center p-4 text-white">Manage products</h1>
<a class="font-weight-bold text-center btn btn-primary text-white d-block m-auto w-25" href="products.php?do=add">Add
    New Product
    +</a>
<table class="table table-hover table-striped table-bordered text-center">
    <tr>
        <th>ID</th>
        <th>title</th>
        <th>description</th>
        <th>price</th>
        <th>added date</th>
        <th>country</th>
        <th>image</th>
        <th>rating</th>
        <th>status</th>
        <th>register status</th>
        <th>cat name</th>
        <th>user name</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    <?php foreach ($row as $x):
        $id = $x['id'];
        $title = $x['title'];
        $desc = $x['des'];
        $price = $x['price'];
        $date = $x['added_date'];
        $country = $x['country'];
        $image = $x['image'];
        $rating = $x['rating'];
        $status = $x['status'];
        $reg_status = $x['reg_status'];
        $cat_name = $x['cat_name'];
        $user_name = $x['user_name'];
        ?>
    <tr>
        <th><?= $id ?></th>
        <th><?= $title ?></th>
        <th><?= $desc ?></th>
        <th><?= $price ?></th>
        <th><?= $date ?></th>
        <th><?= $country ?></th>
        <th>
            <img width="50" src="<?php echo '../layout/images/'.$image ?>" alt="">
        </th>
        <th>
            <?php
            if($rating==1){echo '*';}
            elseif($rating ==2){echo '**';}
            elseif($rating ==3){echo '***';}
            elseif($rating ==4){echo '****';}
            else{echo '*****';}
            ?>
        </th>
        <th>
            <?php
            if($rating==1){echo 'new';}
            elseif($rating ==2){echo 'normal';}
            else{echo 'old';}
            ?>
        </th>
        <th><?= $reg_status ?></th>
        <th><?= $cat_name ?></th>
        <th><?= $user_name ?></th>
        <th><a href=" products.php?do=edit&id=<?= $id ?>"><i class="fa fa-edit text-info"></i></a>
        </th>
        <th><a href="products.php?do=delete&id=<?= $id ?>"><i class="fa fa-trash text-danger"></i></a></th>
    </tr>
    <?php endforeach ?>
</table>

<?php
    }else if($do=='add'){
        $titleErr=$descErr=$priceErr=$countryErr=$ratingErr=$statusErr=$regErr=$catErr=$userErr='';
        $title = $desc = $price = $country = $rating = $status = $reg_status=$cat=$user='';

        //set the path of images
        //echo $imgs;
        //set images err
        $imageErr='';

            if($_SERVER['REQUEST_METHOD']=='POST'){
                //set the target file path (full path)
                $target_file = $imgs.basename($_FILES['image']['name']);
                //echo $target_file;
                //check if image is empty
                $check='';
                if(!file_exists($_FILES['image']['tmp_name'])){
                    $check = false;
                }else{
                 //check if this image or other file
                $check = getimagesize($_FILES['image']['tmp_name']);
            }

                if($check !==false){
                    if(file_exists($target_file)){
                    $imageErr = "sorry this image exists";
            }
        }else{
        //check the type of error if is it empty or wrong file
        if(empty($_FILES['image']['tmp_name'])){
          $imageErr = 'image is required';
        }else{
          $imageErr = 'please upload only images';
        }
      }
            //check title
            if(empty($_POST['title'])){
                $titleErr= 'title is required';
            }else{
                $title = $_POST['title'];
                if(strlen($title)<4){
                    $titleErr= 'title can not be less than 4 letters';
                }elseif(strlen($title)>20){
                    $titleErr= 'title can not be longer than 20 letters';
                }
            }
            //check desc
            if(empty($_POST['desc'])){
                $descErr = 'description is required';
            }else{
                $desc = $_POST['desc'];
            }
            //check price
            if(empty($_POST['price'])){
                $priceErr = 'price is required';
            }else{
                $price = $_POST['price'];
            }
            //check country
            if(empty($_POST['country'])){
                $countryErr = 'country is required';
            }else{
                $country = $_POST['country'];
            }
            //check rating
            if(empty($_POST['rating'])){
                $ratingErr = 'rating is required';
            }else{
                $rating = $_POST['rating'];
            }
            //check status
            if(empty($_POST['status'])){
                $statusErr = 'status is required';
            }else{
                $status = $_POST['status'];
            }
            //check category
            if(empty($_POST['cat'])){
                $catErr = 'category is required';
            }else{
                $cat = $_POST['cat'];
            }
            //check user
            if(empty($_POST['user'])){
                $userErr = 'user is required';
            }else{
                $user = $_POST['user'];
            }

            $reg_status=$_POST['reg_status'];
            $img_name=$_FILES['image']['name'];

            if (empty($titleErr) && empty($descErr) && empty($priceErr) && empty($countryErr) && empty($ratingErr) && empty($statusErr) && empty($regErr)&& empty($imageErr)&& empty($catErr)&& empty($userErr)) {
                $stmt = $db->prepare("INSERT INTO products (title, des, price, country, rating, status, reg_status, added_date,image,cat_id,user_id) VALUES (:title, :des, :price, :country, :rating, :status, :reg_status, now(),:image,:cat,:user)");
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':des', $desc);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':country', $country);
                $stmt->bindParam(':rating', $rating);
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':reg_status', $reg_status);
                $stmt->bindParam(':image', $img_name);
                $stmt->bindParam(':cat', $cat);
                $stmt->bindParam(':user', $user);
            
                $stmt->execute();
                redirect('success','product added successfully','products.php');
            }
    }
        ?>

<section class="p-3 text-center" id="addProduct">
    <h2 class="text-danger">Add new Product</h2>
    <form class="text-dark bg-warning mt-3 rounded py-1 w-50 mx-auto " action="" method="POST"
        enctype="multipart/form-data">

        <div class="form-group">
            <label for="title">Title:</label>
            <input class="w-50 mx-auto form-control" type="text" name="title" id="title">
            <span class="text-danger"><?= $titleErr ?></span>
        </div>

        <div class="form-group">
            <label for="desc">desc:</label>
            <input class="w-50 mx-auto form-control" type="text" name="desc" id="desc">
            <span class="text-danger"><?= $descErr ?></span>
        </div>

        <div class="form-group">
            <label for="price">price:</label>
            <input class="w-50 mx-auto form-control" type="text" name="price" id="price">
            <span class="text-danger"><?= $priceErr ?></span>
        </div>

        <div class="form-group">
            <label for="country">country:</label>
            <select class="w-50 mx-auto form-control" name="country" id="country">
                <option value="">select country</option>
                <option value="EGYPT">EGYPT</option>
                <option value="CHINA">CHINA</option>
                <option value="FRANCE">FRANCE</option>
                <option value="GERMAN">GERMAN</option>
            </select>
            <span class="text-danger"><?= $countryErr ?></span>
        </div>

        <div class="form-group">
            <label for="rating">Rating:</label>
            <select class="w-50 mx-auto form-control" name="rating" id="rating">
                <option value="">......</option>
                <option value="1">*</option>
                <option value="2">**</option>
                <option value="3">***</option>
                <option value="4">****</option>
            </select>
            <span class="text-danger"><?= $ratingErr ?></span>
        </div>

        <div class="form-group">
            <label for="status"> status:</label>
            <select class=" w-50 mx-auto form-control" name="status" id="status">
                <option value="">select status</option>
                <option value="1">new</option>
                <option value="2">normal</option>
                <option value="3">old</option>
            </select>
            <span class="text-danger"><?= $statusErr ?></span>
        </div>

        <div class="form-group">
            <label for="reg_status"> reg_status:</label>
            <select class=" w-50 mx-auto form-control" name="reg_status" id="reg_status">
                <option value="0">not active</option>
                <option value="1">active</option>
            </select>
            <span class="text-danger"><?= $regErr ?></span>
        </div>
        <!-- reg_status -->

        <div class="form-group">
            <label for="cat"> category:</label>
            <select class=" w-50 mx-auto form-control" name="cat" id="cat">
                <option value="">select category</option>
                <?php
                $stmt2 =$db->prepare("SELECT * FROM `categories`");
                $stmt2->execute();
                $cats = $stmt2->fetchAll();
                foreach($cats as $cat):
                ?>
                <option value="<?= $cat['id'] ?>"> <?= $cat['name'] ?> </option>
                <?php endforeach; ?>
            </select>
            <span class="text-danger"><?= $catErr ?></span>
        </div>
        <!-- category -->

        <div class="form-group">
            <label for="user"> user:</label>
            <select class=" w-50 mx-auto form-control" name="user" id="user">
                <option value="">select user</option>
                <?php
                $stmt3 =$db->prepare("SELECT * FROM `users`");
                $stmt3->execute();
                $users = $stmt3->fetchAll();
                foreach($users as $user):
                ?>
                <option value="<?= $user['id'] ?>"> <?= $user['name'] ?> </option>
                <?php endforeach; ?>
            </select>
            <span class="text-danger"><?= $userErr ?></span>
        </div>
        <!-- user -->

        <div class="form-group">
            <label for="image">image:</label>
            <input class="w-50 mx-auto form-control" type="file" name="image" id="image">
            <span class="text-danger"><?= $imageErr ?></span>
        </div>

        <div class="form-group">
            <input class="btn btn-secondary text-white px-5" type="submit" name="add" id="add">
        </div>
    </form>
</section>

<?php
    }else if ($do=='edit'){
        echo "this is edit page";
    }else if ($do=='update'){
        echo "this is update page";
    }else if ($do=='delete'){
        echo "this is delete page";
    }else if ($do=='approve'){
        echo "this is approve page";
    }else{
        echo "this is main page";
    }

    
    include $tpl.'footer.php';
}else{
    header('Location:index.php');
}