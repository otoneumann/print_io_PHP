<?php
require_once 'init.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

// 1. Get the file path first to delete the file from the folder
$stmt = $pdo->prepare("SELECT file_path FROM files WHERE id = ? AND user_id = ?");
$stmt->execute([$_GET['id'], $_SESSION['user_id']]);
$file = $stmt->fetch();

if ($file) {
    // 2. Delete from server folder
    if (file_exists($file['file_path'])) {
        unlink($file['file_path']);
    }
    // 3. Delete from database
    $stmt = $pdo->prepare("DELETE FROM files WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET['id'], $_SESSION['user_id']]);
}

header("Location: dashboard.php");
exit;