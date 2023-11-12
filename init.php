<?php
//include database
include 'admin/db.php';


$tpl  = 'includes/templates/';
$css  = 'layout/css/';
$js   = 'layout/js/';
$lang = 'includes/languages/';
$func ='includes/functions/'; 
$imgs = '../layout/images/';


//{% include}
// include functions
include  $func.'func.php';
 //incloud language
include $lang.'arabic.php';
//incloud language
include $lang.'english.php';
//incloud header;
include $tpl.'header.php'; 

if(!isset($noNav)){
    include $tpl.'nav.php'; //incloud header;
}


?>