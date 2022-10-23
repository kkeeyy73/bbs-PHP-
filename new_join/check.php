<?php
session_start();
require('../library.php');

$form = $_SESSION['form'];
/*入力チェック */
if (isset($_SESSION['form'])){
	$form = $_SESSION['form'];
}else{
	header('Location: index.php');
	exit;
}
/*入力データ登録*/
if($_SERVER['REQUEST_METHOD'] === 'POST'){
	$db = dbconnect();
	$stmt = $db->prepare('INSERT INTO members(username,password)VALUES(?,?)');
	if(!$stmt){
		die($db->eroor);
	}
	$password = password_hash($form['password'],PASSWORD_DEFAULT);
	$stmt->bind_param('ss',$form['name'],$password);
	$success = $stmt->execute();
	if(!$success){
		die($db->error);
	}
	unset($_SESSION['form']);
	header('Location: thanks.html');
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="../style.css">
  <title>新規登録</title>
</head>
<body class="container text-center">
  <main class="form-confirm w-100 m-auto bg-white">
    <h1 class="h3 pt-4 mb-3 fw-normal">新規登録</h1>
    <p class="fs-6">記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
    <form action="" method="post">
        <dl>
            <dt>ニックネーム</dt>
            <dd class="text-secondary text-decoration-underline"><?php echo h($form['name']); ?></dd>
		    <dt>パスワード</dt>
		    <dd class="text-secondary text-decoration-underline">【表示されません】</dd>
	    </dl>
		<div><a href="index.php?action=rewrite" class="btn btn-outline-primary btn-sm" type="submit">書き直す</a> | <input class="btn btn-outline-success btn-sm" type="submit" value="登録する"></div>
        <p class="mt-5 mb-3 text-muted">&copy; 2022</p>
   </form>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>