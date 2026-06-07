<?php include __DIR__.'/../includes/header.php'; require __DIR__.'/../config.php';
$plans = $pdo->query("SELECT * FROM memberships ORDER BY price")->fetchAll();
?>
<h2>Membership Plans</h2>
<div class="grid">
<?php foreach($plans as $m): ?>
  <div class="card">
    <?php if(!empty($m['image_path'])): ?><img src="<?=htmlspecialchars($m['image_path'])?>" alt=""><?php endif; ?>
    <h3><?=htmlspecialchars($m['name'])?></h3>
    <p><?=nl2br(htmlspecialchars($m['features']))?></p>
    <p><strong>LKR <?=number_format($m['price'],2)?></strong> / month</p>
    <a class="btn-primary" href="/pages/contact.php">Apply Now</a>
  </div>
<?php endforeach; ?>
</div>
<?php include __DIR__.'/../includes/footer.php'; ?>
