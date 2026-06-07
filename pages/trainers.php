<?php include __DIR__.'/../includes/header.php'; require __DIR__.'/../config.php';
$trainers = $pdo->query("SELECT * FROM trainers ORDER BY name")->fetchAll();
?>
<h2>Our Trainers</h2>
<div class="grid">
<?php foreach($trainers as $t): ?>
  <div class="card">
    <?php if(!empty($t['photo_path'])): ?><img src="<?=htmlspecialchars($t['photo_path'])?>" alt=""><?php endif; ?>
    <h3><?=htmlspecialchars($t['name'])?></h3>
    <p><em><?=htmlspecialchars($t['specialization'])?></em></p>
    <p><?=htmlspecialchars($t['bio'])?></p>
  </div>
<?php endforeach; ?>
</div>
<?php include __DIR__.'/../includes/footer.php'; ?>
