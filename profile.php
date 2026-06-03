<?php
require_once 'init.php';
$message = '';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Handle Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
    $stmt->execute([$username, $email, $_SESSION['user_id']]);

    if (!empty($_POST['password'])) {
        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ?, password_plain = ? WHERE id = ?");
        $stmt->execute([$hash, $_POST['password'], $_SESSION['user_id']]);
    }

    $_SESSION['username'] = $username;
    $message = "Profile for " . htmlspecialchars($username) . " is updated!";
}

// Fetch current data
$stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<h1>Profile for <?php echo htmlspecialchars($user['username']); ?></h1>
<?php if ($message) echo "<p style='color: green;'>$message</p>"; ?>

<form method="POST">
    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
    <input type="password" name="password" placeholder="New Password (optional)">
    <button type="submit">Update Profile</button>
</form>
<a href="dashboard.php">Back to Dashboard</a>