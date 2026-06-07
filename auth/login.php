<?php include __DIR__.'/../includes/header.php'; require __DIR__.'/../config.php';
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $email=trim($_POST['email']??''); $pass=$_POST['password']??'';
  $stmt=$pdo->prepare("SELECT * FROM users WHERE email=? LIMIT 1"); $stmt->execute([$email]);
  $u=$stmt->fetch();
  if($u && password_verify($pass,$u['password'])){
    $_SESSION['user']=['id'=>$u['id'],'name'=>$u['name'],'email'=>$u['email'],'role'=>$u['role']];
    header('Location: /dashboards/'.$u['role'].'.php'); exit;
  } else { $error='Invalid credentials.'; }
}
?>
<h2 class = "mb-4">Login</h2>
<?php if($error): ?><div class="alert error"><?=$error?></div><?php endif; ?>
<form method="post">
  <label>Email</label>
  <input class = "mw-1" type="email" name="email" required>
  <label>Password</label>
  <input class = "mw-1" type="password" name="password" required>
  <button class="btn-primary">Login</button>
</form>
<?php include __DIR__.'/../includes/footer.php'; ?>
