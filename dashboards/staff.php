<?php 
include __DIR__.'/../includes/header.php'; 
require __DIR__.'/../config.php'; 
require_role(['staff','admin']);

$tab = $_GET['tab'] ?? 'queries';
function get($k,$d=''){ return trim($_GET[$k] ?? $d); }

// Handle query status changes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['change_status'])) {
        $query_id = intval($_POST['query_id']);
        $new_status = $_POST['new_status'] === 'closed' ? 'closed' : 'open';
        
        $pdo->prepare("UPDATE queries SET status=? WHERE id=?")
            ->execute([$new_status, $query_id]);
            
        $_SESSION['success'] = "Query status updated successfully!";
        header("Location: /dashboards/staff.php?tab=queries"); 
        exit;
    }
}

// Display success message if set
if (isset($_SESSION['success'])) {
    echo '<div class="alert success">'.htmlspecialchars($_SESSION['success']).'</div>';
    unset($_SESSION['success']);
}
?>

<h2>Staff Dashboard</h2>
<p class="mb-4">
  <a style="text-decoration: none" class="color-orange <?= $tab === 'queries' ? 'active' : '' ?>" href="?tab=queries">Queries</a> | 
  <a style="text-decoration: none" class="color-orange <?= $tab === 'bookings' ? 'active' : '' ?>" href="?tab=bookings">Bookings</a>
</p>

<?php
// ---------- CUSTOMER QUERIES ----------
if ($tab==='queries'):
  $queries = $pdo->query("SELECT * FROM queries ORDER BY created_at DESC")->fetchAll();
?>
<div class="card">
  <h3>Customer Queries</h3>
  <table>
    <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Message</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
    <?php foreach($queries as $q): ?>
      <tr>
        <td><?=htmlspecialchars($q['name'])?></td>
        <td><?=htmlspecialchars($q['email'])?></td>
        <td><?=htmlspecialchars($q['message'])?></td>
        <td>
          <span class="status-<?= strtolower($q['status']) ?>">
            <?=htmlspecialchars($q['status'])?>
          </span>
        </td>
        <td>
  <form method="post" style="display: inline-block;">
    <input type="hidden" name="query_id" value="<?=$q['id']?>">
    <input type="hidden" name="new_status" value="<?= ($q['status'] == 'open') ? 'closed' : 'open' ?>">
    <button 
      type="submit" 
      name="change_status" 
      class="btn-table <?= ($q['status'] == 'open') ? 'btn-color-open' : 'btn-color-closed' ?>" 
      style="margin-top: 0px;"
      onclick="return confirm('<?= ($q['status'] == 'open') ? 'Close' : 'Re-Open' ?> this query?');"
    >
      <?= ($q['status'] == 'open') ? 'Close Query' : 'Re-Open Query' ?>
    </button>
  </form>
</td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
<?php endif; ?>

<?php
// ---------- BOOKINGS MANAGEMENT ----------
if ($tab==='bookings'):
  if(isset($_GET['cancel_booking'])){
    $bid = intval($_GET['cancel_booking']);
    $pdo->prepare("DELETE FROM bookings WHERE id=?")->execute([$bid]);
    $_SESSION['success'] = "Booking cancelled successfully!";
    header("Location: /dashboards/staff.php?tab=bookings");
    exit;
  }

  $rows = $pdo->query("SELECT b.id,u.name as uname,u.email,c.name as cname,c.schedule,b.created_at
                       FROM bookings b 
                       JOIN users u ON u.id=b.user_id 
                       JOIN classes c ON c.id=b.class_id
                       ORDER BY b.created_at DESC")->fetchAll();
?>
<div class="card">
  <h3>All Bookings</h3>
  <table>
    <tr>
      <th>User</th>
      <th>Email</th>
      <th>Class</th>
      <th>Schedule</th>
      <th>Booked On</th>
      <th>Action</th>
    </tr>
    <?php foreach($rows as $r): ?>
      <tr>
        <td><?=htmlspecialchars($r['uname'])?></td>
        <td><?=htmlspecialchars($r['email'])?></td>
        <td><?=htmlspecialchars($r['cname'])?></td>
        <td><?=htmlspecialchars($r['schedule'])?></td>
        <td><?=htmlspecialchars($r['created_at'])?></td>
        <td>
          <a class="color-orange" href="?tab=bookings&cancel_booking=<?=$r['id']?>" 
             onclick="return confirm('Cancel this booking?');">Cancel</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
<?php endif; ?>

<?php include __DIR__.'/../includes/footer.php'; ?>