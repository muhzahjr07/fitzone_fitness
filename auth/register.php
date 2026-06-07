<?php include __DIR__.'/../includes/header.php'; require __DIR__.'/../config.php';
$success=$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $name=trim($_POST['name']??''); $email=trim($_POST['email']??''); $pass=$_POST['password']??'';
  if(!$name||!$email||!$pass){$error='All fields are required.';}
  else{
    $hash=password_hash($pass,PASSWORD_DEFAULT);
    try{
      $pdo->prepare("INSERT INTO users(name,email,password,role,created_at) VALUES(?,?,?,?,NOW())")->execute([$name,$email,$hash,'customer']);
      $success='Registration successful. You can now log in.';
    }catch(Exception $e){ $error='Email already exists or invalid data.'; }
  }
}
?>
<h2 class = "mb-4">Create Account</h2>
<?php if($success): ?><div class="alert success"><?=$success?></div><?php endif; ?>
<?php if($error): ?><div class="alert error"><?=$error?></div><?php endif; ?>
<form method="post">
  <label>Name</label>
  <input class = "mw-1" name="name" required>
  <label>Email</label>
  <input class = "mw-1" type="email" name="email" required>
  <label>Password</label>
  <input class = "mw-1" type="password" name="password" required>
  <button class="btn-primary">Register</button>
</form>
<?php include __DIR__.'/../includes/footer.php'; ?>
