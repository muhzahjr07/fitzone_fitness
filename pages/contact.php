<?php include __DIR__.'/../includes/header.php'; require __DIR__.'/../config.php';
$success = $error = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $message = trim($_POST['message'] ?? '');
  if (!$name || !$email || !$message) {
    $error = 'All fields are required.';
  } else {
    $stmt = $pdo->prepare("INSERT INTO queries (name,email,message,status,created_at) VALUES (?,?,?,?,NOW())");
    $stmt->execute([$name,$email,$message,'open']);
    $success = 'Your query has been submitted. We will get back to you soon.';
  }
}
?>
<h2 class = "mb-4">Contact / Queries</h2>
<?php if($success): ?><div class="alert success"><?=$success?></div><?php endif; ?>
<?php if($error): ?><div class="alert error"><?=$error?></div><?php endif; ?>
<form method="post">
  <label>Name</label>
  <input class = "mw-1" name="name" required>
  <label>Email</label>
  <input class = "mw-1" type="email" name="email" required>
  <label>Your Message</label>
  <textarea class = "mw-2" name="message" rows="5" required></textarea>
  <button class="btn-primary">Submit</button>
</form>
<?php include __DIR__.'/../includes/footer.php'; ?>
