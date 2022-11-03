<?php
session_start();
require('library.php');

/*変数の初期化*/
$error = '';

/*ログインチェック*/
if(isset($_SESSION['id']) && isset($_SESSION['name'])){
    $id = $_SESSION['id'];
    $name = $_SESSION['name'];
}else{
    header('Location: login.php');
    exit();
}

$db = dbconnect();

/*メッセージの投稿 */
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $message = h($_POST['message']);
    /*禁止文字が含まれていなければデータベースへ登録*/
    if(strpos_array($message, $ngword) === FALSE){
        $stmt = $db->prepare('insert into posts(member_id,message) values(?,?)');
        if(!$stmt){
            die($db->error);
        }
        $stmt->bind_param('is', $id, $message);
        $success = $stmt->execute();
        if(!$success){
            die($db->error);
        }
        header('Location: index.php');
        exit();
    }else {
        /*禁止文字が含まれていた場合は、エラーを返す*/
        $error = "NG";
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <title>ひとこと掲示板</title>
</head>
<body class="container text-center">
  <main class="form-bbs w-100 m-auto bg-light">
    <h1 class="pb-4 mb-4 fw-normal text-success">ひとこと掲示板</h1>
    <form action="" method="post">
        <dl>
            <dt class="text-decoration-underline"><?php echo h($name); ?>さん、メッセージをどうぞ(50文字まで)</dt>
            <dd>
                <textarea name="message" cols="50" rows="5" maxlength="50" lass="form-control" id="exampleFormControlTextarea1"></textarea>
                <?php if($error === "NG"): ?>
                    <p class="text-danger">*&nbsp;禁止文字が含まれています。</p>
                <?php endif; ?>
            </dd>
        </dl>
        <input class="btn btn-outline-success btn-sm mb-2 pb-2" type="submit" value="投稿する"><br>
        <a href="logout.php">ログアウト</a>
   </form>
   <!--memberdb,postdbからデータを取得-->
   <?php  $stmt = $db->prepare('select p.id,p.member_id,p.message,p.created,m.username from posts p,members m
         where m.id=p.member_id order by id desc'); 
         if(!$stmt){
            die($db->error);
         }
         $success = $stmt->execute();
         if(!$success){
            die($db->error);
         }

         $stmt->bind_result($id, $member_id, $message, $created,
         $name);

         while($stmt->fetch()):
    ?>
            <div class="msg card mx-auto mt-3 w-50">
                <div class="card-header w-auto text-sm-start">
                    <p>名前:&emsp;<?php echo h($name); ?>
                    <span class="day">&emsp;<a href="view.php?id=<?php echo h($id); ?>"><?php echo h($created); ?></a></span>
                    </p>
                </div>
                <ul class="list-group list-group-flush w-auto bg-light">
                    <li class="list-group-item"><p class="message"><?php echo h($message); ?></p>
                                                <p class="deleate"><?php if($_SESSION['id'] === $member_id): ?>
                                                [<a href="delete.php?id=<?php echo h($id); ?>" class="text-danger">削除</a>]
                                                <?php endif; ?>
                                                </p>
                    </li>
                </ul>
            </div>
    <?php endwhile; ?>
    <p class="mt-5 mb-3 text-muted">&copy; 2022</p>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>