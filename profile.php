<?php
require_once 'init.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Handle Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $username = trim($_POST['username']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);

    $errors = [];
    if (strlen($username) < 3) $errors[] = "Username must be at least 3 characters.";
    if (!$email) $errors[] = "Invalid email format.";

    if (empty($errors)) {
        // Check for duplicates (excluding current user)
        $stmt = $pdo->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
        $stmt->execute([$username, $email, $_SESSION['user_id']]);
        if ($stmt->fetch()) {
            $errors[] = "Username or Email already taken by another user.";
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->execute([$username, $email, $_SESSION['user_id']]);
        $_SESSION['username'] = $username;

        if (!empty($_POST['password'])) {
            if (strlen($_POST['password']) < 6) {
                set_flash_message("Profile updated, but password was too short and not changed.", "info");
            } else {
                $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hash, $_SESSION['user_id']]);
                set_flash_message("Profile and password updated successfully!", "success");
            }
        } else {
            set_flash_message("Profile updated successfully!", "success");
        }
    } else {
        set_flash_message(implode(" ", $errors), "error");
    }
}

// Fetch current data
$stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

include 'header.php';
?>

<h2>Edit Profile</h2>
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <div>
        <label>Username</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
    </div>
    <div>
        <label>Email</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
    </div>
    <div>
        <label>New Password (leave blank to keep current)</label>
        <input type="password" name="password" placeholder="New Password">
    </div>
    <button type="submit">Update Profile</button>
</form>
<p><a href="dashboard.php">Back to Dashboard</a></p>

<?php include 'footer.php'; ?>