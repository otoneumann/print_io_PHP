<?php
require_once 'init.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf_file']) && !empty($_POST['file_title'])) {
    $uploadDir = 'uploads/';
    $fileName = basename($_FILES['pdf_file']['name']);
    $targetPath = $uploadDir . time() . '_' . $fileName;

    if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $targetPath)) {
        $stmt = $pdo->prepare("INSERT INTO files (user_id, file_path, file_title) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $targetPath, $_POST['file_title']]);
        $message = $_POST['file_title']." - File uploaded successfully!";
    } else {
        $message = "Upload failed.";
    }
}
?>

<h1>Upload PDF</h1>
<p><?php echo $message; ?></p>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="file_title" placeholder="Enter file name" required>
    <input type="file" name="pdf_file" accept="application/pdf" required>
    <button type="submit">Upload</button>
</form>
<a href="dashboard.php">Back to Dashboard</a>