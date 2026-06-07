<?php
// Simple reusable upload helper (stores relative path like /assets/uploads/classes/xxx.png)
function handle_upload(string $field, string $subdir, ?string $existing = null) {
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return $existing; // keep old path if editing
    }
    $file = $_FILES[$field];
    if ($file['error'] !== UPLOAD_ERR_OK) return $existing;

    $allowed = ['jpg','jpeg','png','webp'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) return $existing;

    if ($file['size'] > 2 * 1024 * 1024) return $existing; // 2MB

    $base = "/assets/uploads/" . trim($subdir, "/");
    $absBase = $_SERVER['DOCUMENT_ROOT'] . $base;
    if (!is_dir($absBase)) @mkdir($absBase, 0777, true);

    $name = uniqid("img_") . "." . $ext;
    $targetAbs = $absBase . "/" . $name;
    if (move_uploaded_file($file['tmp_name'], $targetAbs)) {
        return $base . "/" . $name; // relative path to store in DB
    }
    return $existing;
}
