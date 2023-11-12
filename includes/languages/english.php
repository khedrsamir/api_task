<?php 
function eng($word){
    static $lang = array(
        'رساله' => 'message',
        'تحكم' => 'control',
        'احمد' => 'Ahmed',
    );
    return $lang[$word];
}
