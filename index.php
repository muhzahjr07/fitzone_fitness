<?php include __DIR__.'/includes/header.php'; require __DIR__.'/config.php'; ?>
<section class="herosection">
        <div class="heroimage"><div class="hero">
        <h2>Stronger. Healthier. Happier.</h2>
        <p>Join FitZone Fitness and access world-class equipment, certified trainers, dynamic classes, and a supportive community.</p>
        <a class="btn-primary" href="/auth/register.php">Join Now</a>
    </div></div>
</section>
<h2 class="mt-4">Popular Classes</h2>
<div class="grid">
<?php
$classes = $pdo->query("SELECT id,name,description,schedule,image_path FROM classes ORDER BY id DESC LIMIT 4")->fetchAll();
foreach($classes as $c): ?>
  <div class="card">
    <?php if(!empty($c['image_path'])): ?><img src="<?=htmlspecialchars($c['image_path'])?>" alt=""><?php endif; ?>
    <h3><?=htmlspecialchars($c['name'])?></h3>
    <p><?=htmlspecialchars(substr($c['description'],0,120))?>...</p>
    <span class="badge"><?=htmlspecialchars($c['schedule'])?></span>
  </div>
<?php endforeach; ?>
</div>

<h2 class="mt-4">Featured Trainers</h2>
<div class="grid">
<?php
$trainers = $pdo->query("SELECT id,name,specialization,bio,photo_path FROM trainers ORDER BY id DESC LIMIT 4")->fetchAll();
foreach($trainers as $t): ?>
  <div class="card">
    <?php if(!empty($t['photo_path'])): ?><img src="<?=htmlspecialchars($t['photo_path'])?>" alt=""><?php endif; ?>
    <h3><?=htmlspecialchars($t['name'])?></h3>
    <p><em><?=htmlspecialchars($t['specialization'])?></em></p>
    <p><?=htmlspecialchars(substr($t['bio'],0,100))?>...</p>
  </div>
<?php endforeach; ?>
</div>

<h2 class="mt-4">Membership Plans</h2>
<div class="grid">
<?php
$plans = $pdo->query("SELECT id,name,price,features,image_path FROM memberships ORDER BY price ASC LIMIT 3")->fetchAll();
foreach($plans as $m): ?>
  <div class="card">
    <?php if(!empty($m['image_path'])): ?><img src="<?=htmlspecialchars($m['image_path'])?>" alt=""><?php endif; ?>
    <h3><?=htmlspecialchars($m['name'])?></h3>
    <p><?=nl2br(htmlspecialchars(substr($m['features'],0,120)))?>...</p>
    <p><strong>LKR <?=number_format($m['price'],2)?></strong> / month</p>
  </div>
<?php endforeach; ?>
</div>

<h2 class="mt-4">From the Blog</h2>
<div class="grid">
<?php
$posts = $pdo->query("SELECT id,title,category,image_path,LEFT(content,140) excerpt FROM blog_posts ORDER BY created_at DESC LIMIT 3")->fetchAll();
foreach($posts as $p): ?>
  <div class="card">
    <?php if(!empty($p['image_path'])): ?><img src="<?=htmlspecialchars($p['image_path'])?>" alt=""><?php endif; ?>
    <span class="badge"><?=htmlspecialchars($p['category'])?></span>
    <h3><?=htmlspecialchars($p['title'])?></h3>
    <p><?=htmlspecialchars($p['excerpt'])?>...</p>
    <a class="btn-primary" href="/pages/blog.php?post=<?=$p['id']?>">Read</a>
  </div>
<?php endforeach; ?>
</div>
<section class="contactsection">
        <div class="contactimage"><div class="contacthero">
        <h2>Stay Updated with FitZone.</h2>
        <p>Contact FitZone Fitness and learn more about world-class equipment, certified trainers, dynamic classes, and a supportive community.</p>
        <a class="btn-primary" href="/pages/contact.php">Contact</a>
    </div></div>
</section>

<?php include __DIR__.'/includes/footer.php'; ?>
