<?php 
 /*
 **v 1.0
 ** to set the title every page
 **check if $title variable exist or not and echo it
 */
function setTitle(){
    global $title;

    if(isset($title)){
        echo $title;
    }else{
        echo 'default';
    }
}

function redirect($class,$msg,$url,$sec=2){
    echo "<h2 class='alert alert-$class'>$msg</h2>";
    echo "<h2 class='alert alert-info'>you will be redirect after $sec seconds</h2>";

    header("refresh:$sec;url=$url");
}

function checkDb($col,$table,$value){
    global $db;
    $check = $db->prepare("SELECT $col FROM $table WHERE $col=?");
    $check->execute(array($value));
    $count = $check->rowCount();
    return $count;
}
?>