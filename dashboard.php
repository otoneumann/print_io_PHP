<?php
require_once 'init.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT id, file_title, file_path FROM files WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$files = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<h2>Your Dashboard</h2>
<p>Hello, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</p>

<h3>Your Files</h3>
<?php if (empty($files)): ?>
    <p>No files uploaded yet. <a href="upload.php">Upload your first file</a>.</p>
<?php else: ?>
    <table border="1" cellpadding="10" style="width:100%; border-collapse: collapse; margin-bottom: 20px;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th align="left">Title</th>
                <th align="center">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($files as $file): ?>
            <tr>
                <td>
                    <a href="<?php echo htmlspecialchars($file['file_path']); ?>" target="_blank">
                        <?php echo htmlspecialchars($file['file_title']); ?>
                    </a>
                </td>
                <td align="center">
                    <a href="edit_file.php?id=<?php echo $file['id']; ?>">Edit</a> | 
                    <form method="POST" action="delete_file.php" style="display:inline;" onsubmit="return confirm('Delete this file?')">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="id" value="<?php echo $file['id']; ?>">
                        <button type="submit" style="background:none; border:none; color:red; text-decoration:underline; cursor:pointer; padding:0;">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<a href="upload.php" style="display: inline-block; background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px;">Upload New PDF</a>

<?php include 'footer.php'; ?>