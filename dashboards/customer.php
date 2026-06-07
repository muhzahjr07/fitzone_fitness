<?php 
include __DIR__.'/../includes/header.php'; 
require __DIR__.'/../config.php'; 
require_role(['customer','staff','admin']);

$uid = $_SESSION['user']['id'];

// Handle fetch bookings
$stmt=$pdo->prepare("SELECT b.id,c.name,c.schedule,b.created_at 
                     FROM bookings b 
                     JOIN classes c ON c.id=b.class_id 
                     WHERE b.user_id=? 
                     ORDER BY b.created_at DESC");
$stmt->execute([$uid]);

// Display success message if set
if (isset($_SESSION['success'])) {
    echo '<div class="alert success">'.htmlspecialchars($_SESSION['success']).'</div>';
    unset($_SESSION['success']);
}
?>

<h2 class="mb-4">Customer Dashboard</h2>
<div class="card">
  <h3>My Bookings</h3>
  <table border="1" cellpadding="6">
    <tr>
      <th>Class</th>
      <th>Schedule</th>
      <th>Booked On</th>
      <th>Action</th>
    </tr>
    <?php foreach($stmt as $b): ?>
      <tr>
        <td><?=htmlspecialchars($b['name'])?></td>
        <td><?=htmlspecialchars($b['schedule'])?></td>
        <td><?=htmlspecialchars($b['created_at'])?></td>
        <td>
          <a class="color-orange" href="/dashboards/cancel_booking.php?id=<?=$b['id']?>" 
             onclick="return confirm('Cancel this booking?');">
             Cancel
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
<?php include __DIR__.'/../includes/footer.php'; ?>