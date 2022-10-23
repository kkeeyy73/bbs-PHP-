<?php
session_start();
require('library.php');

$error = [];
$name = '';
$password = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = h($_POST['name']);
    $password = h($_POST['password']);
    if($name === '' || $password === ''){
        $error['login'] = 'blank';
    }else{
        /*ログインチェック */
        $db = dbconnect();
        $stmt = $db->prepare('select id, username, password from members where username=? limit 1');
        if(!$stmt){
            die($db->error);
        }
        $stmt->bind_param('s', $name);
        $success = $stmt->execute();
        if(!$success){
            die($db->error);
        }
        $stmt->bind_result($id, $name, $hash);
        $stmt->fetch();

        if(password_verify($password, $hash)){
            /*ログイン成功 */
            session_regenerate_id();
            $_SESSION['id'] = $id;
            $_SESSION['name'] = $name;
            header('Location: index.php');
            exit();
        }else{
            /*ログイン失敗 */
            $error['login'] ='failed';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <title>サインインページ</title>
</head>
<body class="text-center">
  <main class="form-signin w-100 m-auto">
    <h1 class="h3 mb-3 fw-normal">サインインをどうぞ</h1>
    <p class="fs-6">フォームに必要事項をご記入ください。</p>
    <form action="" method="post" enctype="multipart/form-data">
      <div class="form-floating">
        <input type="text" name="name" size="35" maxlength="255" value="<?php echo h($name); ?>" class="form-control" id="floatingInput" placeholder="ニックネーム">
        <label for="floatingInput">ニックネーム</label>
        <?php if(isset($error['login']) && $error['login'] === 'blank'): ?>
            <p class="error">* ニックネームとパスワードをご記入ください</p>
            <?php endif; ?>
            <?php if(isset($error['login']) && $error['login'] === 'failed'): ?>
                <p class="error">* ログインに失敗しました。<br>
                正しくご記入ください。</p>
            <?php endif; ?>
      </div>
      <div class="form-floating">
        <input type="password"  name="password" size="35" maxlength="255" value="<?php echo h($password); ?>" class="form-control" id="floatingPassword" placeholder="パスワード">
        <label for="floatingPassword">パスワード</label>
      </div>
      <button class="w-100 btn btn-lg btn-success mt-2" type="submit">サインイン</button>
      <div class="h4 mt-3 fs-6 fw-light">
        <a href="new_join/index.php">新規の方はこちら</a>
      </div>
      <p class="mt-5 mb-3 text-muted">&copy; 2022</p>
   </form>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>