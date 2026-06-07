<?php
include __DIR__.'/../includes/header.php';
require __DIR__.'/../config.php';

$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];

if (!empty($query)) {
    // Search classes
    $stmt = $pdo->prepare("SELECT id, name, description, schedule, image_path FROM classes 
                          WHERE name LIKE ? OR description LIKE ?");
    $stmt->execute(["%$query%", "%$query%"]);
    $results['classes'] = $stmt->fetchAll();

    // Search trainers
    $stmt = $pdo->prepare("SELECT id, name, specialization, bio, photo_path FROM trainers 
                          WHERE name LIKE ? OR specialization LIKE ? OR bio LIKE ?");
    $stmt->execute(["%$query%", "%$query%", "%$query%"]);
    $results['trainers'] = $stmt->fetchAll();

    // Search memberships
    $stmt = $pdo->prepare("SELECT id, name, price, features, image_path FROM memberships 
                          WHERE name LIKE ? OR features LIKE ?");
    $stmt->execute(["%$query%", "%$query%"]);
    $results['memberships'] = $stmt->fetchAll();

    // Search blog posts
    $stmt = $pdo->prepare("SELECT id, title, category, content, image_path FROM blog_posts 
                          WHERE title LIKE ? OR category LIKE ? OR content LIKE ?");
    $stmt->execute(["%$query%", "%$query%", "%$query%"]);
    $results['blog'] = $stmt->fetchAll();
}
?>

<h2>Search Results for "<?= htmlspecialchars($query) ?>"</h2>

<?php if (empty($query)): ?>
    <div class="alert alert-info">Please enter a search term</div>
<?php else: ?>
    <div class="search-results">
        <?php if (!empty($results['classes'])): ?>
            <h3>Classes</h3>
            <div class="grid">
                <?php foreach ($results['classes'] as $class): ?>
                    <div class="card">
                        <?php if (!empty($class['image_path'])): ?>
                            <img src="<?= htmlspecialchars($class['image_path']) ?>" alt="">
                        <?php endif; ?>
                        <h4><?= htmlspecialchars($class['name']) ?></h4>
                        <p><?= htmlspecialchars(substr($class['description'], 0, 100)) ?>...</p>
                        <a href="/pages/classes.php#class-<?= $class['id'] ?>" class="btn-primary">View Class</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($results['trainers'])): ?>
            <h3>Trainers</h3>
            <div class="grid">
                <?php foreach ($results['trainers'] as $trainer): ?>
                    <div class="card">
                        <?php if (!empty($trainer['photo_path'])): ?>
                            <img src="<?= htmlspecialchars($trainer['photo_path']) ?>" alt="">
                        <?php endif; ?>
                        <h4><?= htmlspecialchars($trainer['name']) ?></h4>
                        <p><em><?= htmlspecialchars($trainer['specialization']) ?></em></p>
                        <a href="/pages/trainers.php#trainer-<?= $trainer['id'] ?>" class="btn-primary">View Trainer</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($results['memberships'])): ?>
            <h3>Memberships</h3>
            <div class="grid">
                <?php foreach ($results['memberships'] as $plan): ?>
                    <div class="card">
                        <?php if (!empty($plan['image_path'])): ?>
                            <img src="<?= htmlspecialchars($plan['image_path']) ?>" alt="">
                        <?php endif; ?>
                        <h4><?= htmlspecialchars($plan['name']) ?></h4>
                        <p>LKR <?= number_format($plan['price'], 2) ?></p>
                        <a href="/pages/memberships.php#plan-<?= $plan['id'] ?>" class="btn-primary">View Plan</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($results['blog'])): ?>
            <h3>Blog Posts</h3>
            <div class="grid">
                <?php foreach ($results['blog'] as $post): ?>
                    <div class="card">
                        <?php if (!empty($post['image_path'])): ?>
                            <img src="<?= htmlspecialchars($post['image_path']) ?>" alt="">
                        <?php endif; ?>
                        <h4><?= htmlspecialchars($post['title']) ?></h4>
                        <p><?= htmlspecialchars(substr($post['content'], 0, 100)) ?>...</p>
                        <a href="/pages/blog.php?post=<?= $post['id'] ?>" class="btn-primary">Read Post</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (empty(array_filter($results))): ?>
            <div class="alert alert-info">No results found for "<?= htmlspecialchars($query) ?>"</div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php include __DIR__.'/../includes/footer.php'; ?>