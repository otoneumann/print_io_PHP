<?php
require_once 'init.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT id, file_title, file_path FROM files WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$files = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Dashboard: <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
<a href="profile.php">Profile</a> | <a href="logout.php">Logout</a>

<h2>Your Files</h2>
<?php if (empty($files)): ?>
    <p>No files uploaded yet.</p>
<?php else: ?>
    <ul>
        <?php foreach ($files as $file): ?>
            <li>
                <a href="<?php echo htmlspecialchars($file['file_path']); ?>" target="_blank">
                    <?php echo htmlspecialchars($file['file_title']); ?>
                </a>
                <a href="edit_file.php?id=<?php echo $file['id']; ?>">[Edit]</a>
                <a href="delete_file.php?id=<?php echo $file['id']; ?>" onclick="return confirm('Delete this file?')">[Delete]</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<a href="upload.php">Upload New File</a>