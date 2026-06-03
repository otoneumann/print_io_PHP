<?php
require_once 'init.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch user's files
$stmt = $pdo->prepare("SELECT file_title, file_path FROM files WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$files = $stmt->fetchAll();
?>

<h1>Welcome, <?php echo htmlspecialchars(strtoupper($_SESSION['username'])); ?>!</h1>
<a href="profile.php">Edit Profile <?php echo strtoupper($_SESSION['username']) ?></a>
<p>This is your private dashboard.</p>

<h2>Your Files</h2>
<ul>
    <?php foreach ($files as $file): ?>
        <li>
            <a href="<?php echo htmlspecialchars($file['file_path']); ?>" target="_blank">
                <?php echo htmlspecialchars($file['file_title']); ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<ul>
    <li><a href="upload.php">Upload New File</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>