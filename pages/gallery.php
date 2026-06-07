<?php include __DIR__.'/../includes/header.php'; require __DIR__.'/../config.php';
$imgs = $pdo->query("SELECT * FROM gallery ORDER BY id DESC")->fetchAll();
?>
<h2>Gallery</h2>
<div class="grid">
<?php foreach($imgs as $g): ?>
  <div class="card">
    <?php if(!empty($g['image_path'])): ?><img src="<?=htmlspecialchars($g['image_path'])?>" alt=""><?php endif; ?>
    <p><?=htmlspecialchars($g['caption'])?></p>
  </div>
<?php endforeach; ?>
</div>
<?php include __DIR__.'/../includes/footer.php'; ?>
