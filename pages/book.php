<?php
require __DIR__.'/../config.php';
require_role(['customer','admin','staff']); // Combined roles from both versions

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['class_id'])) {
    $class_id = (int)($_POST['class_id'] ?? 0);
    $user_id = $_SESSION['user']['id'];

    try {
        // Verify class exists
        $stmt = $pdo->prepare("SELECT id FROM classes WHERE id = ?");
        $stmt->execute([$class_id]);
        
        if ($stmt->rowCount() === 0) {
            $_SESSION['error'] = "Class not found";
        } else {
            // Check if user already booked this class (prevent duplicates)
            $stmt = $pdo->prepare("SELECT id FROM bookings WHERE user_id = ? AND class_id = ?");
            $stmt->execute([$user_id, $class_id]);
            
            if ($stmt->rowCount() > 0) {
                $_SESSION['error'] = "You've already booked this class";
            } else {
                // Create booking with timestamp
                $pdo->prepare("INSERT INTO bookings (user_id, class_id, created_at) VALUES (?, ?, NOW())")
                   ->execute([$user_id, $class_id]);
                
                $_SESSION['success'] = "Class booked successfully!";
            }
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error processing your booking";
        error_log("Booking error: " . $e->getMessage());
    }
}

header("Location: /pages/classes.php");
exit;
?>