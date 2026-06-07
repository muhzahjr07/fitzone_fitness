<?php
require __DIR__.'/../config.php';
require_role(['customer']);

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Invalid request";
    header("Location: /dashboards/customer.php");
    exit;
}

$booking_id = (int)$_GET['id'];
$uid = $_SESSION['user']['id'];

try {
    // First verify the booking exists and belongs to user
    $stmt = $pdo->prepare("SELECT id FROM bookings WHERE id = ? AND user_id = ?");
    $stmt->execute([$booking_id, $uid]);
    
    if ($stmt->rowCount() === 0) {
        $_SESSION['error'] = "Booking not found or not authorized";
    } else {
        // Then delete it
        $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ? AND user_id = ?");
        $stmt->execute([$booking_id, $uid]);
        $_SESSION['success'] = "Booking cancelled successfully!";
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Error cancelling booking";
    error_log("Booking cancellation error: " . $e->getMessage());
}

header("Location: /dashboards/customer.php");
exit;