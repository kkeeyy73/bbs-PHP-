<?php
/*htmlspeialcharsでエスケープ処理*/
function h($value){
    return htmlspecialchars($value, ENT_QUOTES);
}

/*データベースへ接続 */
function dbconnect(){
<<<<<<< HEAD
    $db = new mysqli('mysql209.phy.lolipop.lan','LAA1473327','Akb2101','LAA1473327-bbsdb');
=======
    $db = new mysqli('','','','');
>>>>>>> 5b4c3d475ecd4f708087a37be8ee22e3aa853137
    if(!$db){
		die($db->error);
	}
    return $db;
}

/*指定文字*/
$ngword = [];

/*strposを配列で使用*/
function strpos_array($haystack, $needle, $is_index = FALSE){
    $ary = $needle;
    $pos = 0;
    if(!is_array($needle)){
        $ary = [$needle];
    }
    foreach($ary as $key => $str){
        $pos = strpos($haystack, $str);
        if($pos === false){
            continue;
        }
        if($is_index){
            return $key;
        }
        return $pos;
    }
    return FALSE;
}
?>
