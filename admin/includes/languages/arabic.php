<?php 
function arab($word){
    static $lang = array(
        'message' => 'رساله',
        'control' => 'تحكم',
        'Ahmed' => 'احمد',
    );
    return $lang[$word];
}