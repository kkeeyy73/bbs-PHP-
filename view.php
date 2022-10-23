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
/*idチェック*/
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if(!$id){
    header('Location: index.php');
    exit();
}

$db = dbconnect();
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
    <p><a href="index.php">一覧にもどる</a></p>
    
   <!--memberdb,postdbからデータを取得-->
   <?php  $stmt = $db->prepare('select p.id,p.member_id,p.message,p.created,m.username from posts p,members m
         where p.id=? and m.id=p.member_id order by id desc'); 
         if(!$stmt){
            die($db->error);
         }
         $stmt->bind_param('i',$id);
         $success = $stmt->execute();
         if(!$success){
            die($db->error);
         }

         $stmt->bind_result($id, $member_id, $message, $created,
         $name);

         if($stmt->fetch()):
    ?>
    <div class="msg card mx-auto mt-3" style="width: 50rem">
        <div class="card-header">
            <p><?php echo h($name); ?></span>
            <span class="day"><a href="view.php?id=<?php echo h($id); ?>"><?php echo h($created); ?></a>
            </p>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><p class="message"><?php echo h($message); ?></p>
                                        <p class="deleate"><?php if($_SESSION['id'] === $member_id): ?>
            [                           <a href="delete.php?id=<?php echo h($id); ?>" style="color: #F33;">削除</a>]
                                        <?php endif; ?>
                                        </p>
        </ul>
        <?php else: ?>
          <p>その投稿は削除されたか、URLが間違えています</p>
        <?php endif; ?>
    </div>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>