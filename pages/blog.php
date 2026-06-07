<?php include __DIR__.'/../includes/header.php'; require __DIR__.'/../config.php';
$postId = intval($_GET['post'] ?? 0);

if ($postId) {
  $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id=?");
  $stmt->execute([$postId]);
  $post = $stmt->fetch();
  if (!$post) { echo "<h2>Post not found</h2>"; include __DIR__.'/../includes/footer.php'; exit; }
  ?>
  <article class="card">
    <?php if(!empty($post['image_path'])): ?><img src="<?=htmlspecialchars($post['image_path'])?>" alt=""><?php endif; ?>
    <span class="badge"><?=htmlspecialchars($post['category'])?></span>
    <h2><?=htmlspecialchars($post['title'])?></h2>
    <p><?=nl2br(htmlspecialchars($post['content']))?></p>
    <p><small>Published: <?=htmlspecialchars($post['created_at'])?></small></p>
  </article>
  <?php
  include __DIR__.'/../includes/footer.php'; exit;
}

$cat = $_GET['cat'] ?? '';
$cats = ['Workouts','Nutrition','Success Stories'];
$query = "SELECT id,title,category,image_path,LEFT(content,180) excerpt,created_at FROM blog_posts";
$params = [];
if ($cat && in_array($cat, $cats)) {
  $query .= " WHERE category=?"; $params[] = $cat;
}
$query .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($query); $stmt->execute($params);
$posts = $stmt->fetchAll();
?>
<h2>Blog</h2>
<p>Explore our latest posts on <strong>Workouts</strong>, <strong>Nutrition</strong>, and <strong>Success Stories</strong>.</p>
<p>
  Filter:
  <a class = "color-orange" href="/pages/blog.php">All</a> |
  <a class = "color-orange" href="/pages/blog.php?cat=Workouts">Workouts</a> |
  <a class = "color-orange" href="/pages/blog.php?cat=Nutrition">Nutrition</a> |
  <a class = "color-orange" href="/pages/blog.php?cat=Success Stories">Success Stories</a>
</p>
<div class="grid">
<?php foreach($posts as $p): ?>
  <div class="card">
    <?php if(!empty($p['image_path'])): ?><img src="<?=htmlspecialchars($p['image_path'])?>" alt=""><?php endif; ?>
    <span class="badge"><?=htmlspecialchars($p['category'])?></span>
    <h3><?=htmlspecialchars($p['title'])?></h3>
    <p><?=htmlspecialchars($p['excerpt'])?>...</p>
    <a class="btn-primary" href="/pages/blog.php?post=<?=$p['id']?>">Read</a>
  </div>
<?php endforeach; ?>
</div>
<?php include __DIR__.'/../includes/footer.php'; ?>
