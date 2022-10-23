<?php
session_start();
require('library.php');
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
  <main class="form-bbs w-100 m-auto bg-white">
    <h1 class="h3 pt-4 mb-3 fw-normal">ひとこと掲示板</h1>
    <form action="" method="post">
        <dl>
            <dt class="text-decoration-underline"><?php echo h($name); ?>さん、メッセージをどうぞ</dt>
            <dd>
                <textarea name="message" cols="50" rows="5" lass="form-control" id="exampleFormControlTextarea1"></textarea>
            </dd>
        </dl>
        <input class="btn btn-primary btn-sm mb-2" type="submit" value="投稿する"><br>
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
            <div class="msg card mx-auto mt-3" style="width: 50rem">
                <div class="card-header">
                    <p>名前: <span><?php echo h($name); ?></span>
                    <span class="day"><a href="view.php?id=<?php echo h($id); ?>"><?php echo h($created); ?></a>
                    </p>
                </div>
                <ul class="list-group list-group-flush">
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