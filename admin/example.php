<?php

$do='';

if (isset($_GET['do'])){
    $do = $_GET['do'];
}else{
    $do = 'main';
}


if ($do=='main'){
    echo "<a href='example.php?do=add'>add page</a>";
    echo "<a href='example.php?do=edit'>edit page</a>";
}else if($do=='add'){
    echo "this is add page";
}else if ($do=='edit'){
    echo "this is edit page";
}else if ($do=='update'){
    echo "this is update page";
}else if ($do=='delete'){
    echo "this is delete page";
}else{
   echo "this is main page";
}

