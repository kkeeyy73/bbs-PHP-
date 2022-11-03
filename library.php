<?php
/*htmlspeialcharsでエスケープ処理*/
function h($value){
    return htmlspecialchars($value, ENT_QUOTES);
}

/*データベースへ接続 */
function dbconnect(){
    $db = new mysqli('mysql209.phy.lolipop.lan','LAA1473327','Akb2101','LAA1473327-bbsdb');
    if(!$db){
		die($db->error);
	}
    return $db;
}

/*指定文字*/
$ngword = ['死', 'しね', 'しぬ', '殺', 'コロス', 'さつがい', 'サツガイ', 'ちんこ', 'うんこ', 'まんこ', 'ちんちん', 'sex', 'SEX'];

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