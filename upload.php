<?php
require_once 'init.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf_file']) && !empty($_POST['file_title'])) {
    check_csrf();
    
    // 1. Check file size (e.g., 5MB limit)
    $maxSize = 5 * 1024 * 1024;
    if ($_FILES['pdf_file']['size'] > $maxSize) {
        set_flash_message("Error: File is too large. Maximum size is 5MB.", "error");
    } else {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($_FILES['pdf_file']['tmp_name']);

        if ($mimeType === 'application/pdf') {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileName = preg_replace("/[^a-zA-Z0-9\._-]/", "_", basename($_FILES['pdf_file']['name']));
            $targetPath = $uploadDir . time() . '_' . bin2hex(random_bytes(8)) . '_' . $fileName;

            if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $targetPath)) {
                $stmt = $pdo->prepare("INSERT INTO files (user_id, file_path, file_title) VALUES (?, ?, ?)");
                $stmt->execute([$_SESSION['user_id'], $targetPath, $_POST['file_title']]);
                set_flash_message("File '" . htmlspecialchars($_POST['file_title']) . "' uploaded successfully!", "success");
                header("Location: dashboard.php");
                exit;
            } else {
                set_flash_message("Upload failed due to server error.", "error");
            }
        } else {
            set_flash_message("Error: Only PDF files are allowed.", "error");
        }
    }
}

include 'header.php';
?>

<h2>Upload PDF</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <div>
        <label>File Title</label>
        <input type="text" name="file_title" placeholder="Enter file name" required>
    </div>
    <div>
        <label>Select PDF (Max 5MB)</label>
        <input type="file" name="pdf_file" accept="application/pdf" required>
    </div>
    <button type="submit">Upload</button>
</form>
<p><a href="dashboard.php">Back to Dashboard</a></p>

<?php include 'footer.php'; ?>