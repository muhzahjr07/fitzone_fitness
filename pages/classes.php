<?php 
include __DIR__.'/../includes/header.php'; 
require __DIR__.'/../config.php';

// Display success/error messages if set
if (isset($_SESSION['success'])) {
    echo '<div class="alert success">'.htmlspecialchars($_SESSION['success']).'</div>';
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo '<div class="alert error">'.htmlspecialchars($_SESSION['error']).'</div>';
    unset($_SESSION['error']);
}

$classes = $pdo->query("SELECT * FROM classes ORDER BY id")->fetchAll();
?>
<h2>Classes</h2>
<div class="grid">
<?php foreach($classes as $c): ?>
  <div class="card">
    <?php if(!empty($c['image_path'])): ?><img src="<?=htmlspecialchars($c['image_path'])?>" alt=""><?php endif; ?>
    <div class="card-content">
      <h3><?=htmlspecialchars($c['name'])?></h3>
      <p><?=htmlspecialchars($c['description'])?></p>
      <p class="schedule"><strong>Schedule:</strong> <?=htmlspecialchars($c['schedule'])?></p>
    </div>
    <div class="btn-container">
      <?php if(!empty($_SESSION['user'])): ?>
        <form method="post" action="/pages/book.php">
          <input type="hidden" name="class_id" value="<?=$c['id']?>">
          <button class="btn-primary">Book Now</button>
        </form>
      <?php else: ?>
        <a class="btn-primary" href="/auth/login.php">Login to Book</a>
      <?php endif; ?>
    </div>
  </div>
<?php endforeach; ?>
</div>
<?php include __DIR__.'/../includes/footer.php'; ?>