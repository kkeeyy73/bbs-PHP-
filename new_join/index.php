<?php
session_start();
require('../library.php');
/*入力情報の訂正、または新規登録*/
if(isset($_GET['action']) && $_GET['action'] === 'rewrite' && isset($_SESSION['form'])){
    $form = $_SESSION['form'];
}else{
    /*変数初期化*/
    $form = [
        'name'=>'',
        'password'=>'',
    ];
}
$error = [];
/*フォームの内容をチェック */
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $form['name'] = h($_POST['name']);
    /*nameのチェック */
    if ($form['name'] === ''){
        $error['name'] = 'blank';
    }
    /*passwordのチェック */
    $form['password'] = h($_POST['password']);                    
    if ($form['password'] === ''){
        $error['password'] = 'blank';
    } else if(mb_strlen($form['password']) <= 6){
        $error['password'] = 'length';
    }
    /*エラーがなければcheck.phpへ*/
    if (empty($error)){
        $_SESSION['form'] = $form;
        if (($error['name'] == '') && ($error['password'] == '')){
            header('Location: check.php');
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
  integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="../style.css">
  <title>新規登録</title>
</head>
<body class="text-center">
  <main class="form-signin w-100 m-auto">
    <h1 class="text-success">ひとこと掲示板</h1>
    <h4 class="mb-3 fw-normal">新規登録</h4>
    <p class="fs-6">フォームに必要事項をご記入ください。</p>
    <form action="" method="post" enctype="multipart/form-data">
      <div class="form-floating">
        <input type="text" name="name" size="35" maxlength="255" value="<?php echo h($form['name']); ?>" class="form-control" id="floatingInput" placeholder="ニックネーム">
        <label for="floatingInput">ニックネーム</label>
        <?php if (isset($error['name']) && $error['name'] === 'blank'): ?>
            <p class="error">* ニックネームを入力してください</p>
        <?php endif;?>
      </div>
      <div class="form-floating">
        <input type="password"  name="password" size="10" maxlength="20" value="<?php echo h($form['password']); ?>" class="form-control" id="floatingPassword" placeholder="パスワード">
        <label for="floatingPassword">パスワード(6以上文字以上)</label>
        <?php if (isset($error['password']) && $error['password'] === 'blank'): ?>
            <p class="error">* パスワードを入力してください</p>
        <?php endif; ?>
        <?php if (isset($error['password']) && $error['password'] === 'length'): ?>
            <p class="error">* パスワードは6文字以上で入力してください</p>
        <?php endif; ?>
      </div>
      <button class="w-100 btn btn-lg btn-success mt-2" type="submit">入力内容を確認する</button>
      <div class="h4 mt-3 fs-6 fw-light">
        <a href="../login.php">登録済みの方はこちら</a>
      </div>
      <p class="mt-5 mb-3 text-muted">&copy; 2022</p>
   </form>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>