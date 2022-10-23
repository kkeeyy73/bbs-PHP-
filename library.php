<?php
/*htmlspeialcharsでエスケープ処理*/
function h($value){
    return htmlspecialchars($value, ENT_QUOTES);
}

/*DBへ接続 */
function dbconnect(){
    $db = new mysqli('localhost','root','2221','bbsdb');
    if(!$db){
		die($db->error);
	}
    return $db;
}
?>