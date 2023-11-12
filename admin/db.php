<?php 

$dsn = 'mysql:host=localhost;dbname=e_comm';

$users ='root';
$pass ='';

try{
    $db = new PDO($dsn,$users,$pass);
    $db-> setATTRIBUTE(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

catch(PDOException $e){
    echo $e->getMassage();
}