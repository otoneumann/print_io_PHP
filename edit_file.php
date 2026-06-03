<?php
require_once 'init.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $stmt = $pdo->prepare("UPDATE files SET file_title = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$_POST['file_title'], $_GET['id'], $_SESSION['user_id']]);
    header("Location: dashboard.php");
    exit;
}

$stmt = $pdo->prepare("SELECT file_title FROM files WHERE id = ? AND user_id = ?");
$stmt->execute([$_GET['id'], $_SESSION['user_id']]);
$file = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$file) { header("Location: dashboard.php"); exit; }
?>

<h1>Edit Title</h1>
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <input type="text" name="file_title" value="<?php echo htmlspecialchars($file['file_title']); ?>" required>
    <button type="submit">Update</button>
</form>