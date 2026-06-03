<?php
require_once 'init.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // 2. Find user by username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        set_flash_message("Welcome back, " . $user['username'] . "!", "success");
        header("Location: dashboard.php");
        exit;
    } else {
        set_flash_message("Invalid username or password.", "error");
    }
}

include 'header.php';
?>

<h2>Login</h2>
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <div>
        <label>Username</label>
        <input type="text" name="username" placeholder="Username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
    </div>
    <div>
        <label>Password</label>
        <input type="password" name="password" placeholder="Password" required>
    </div>
    <button type="submit">Login</button>
</form>
<p>Don't have an account? <a href="index.php">Register here</a></p>

<?php include 'footer.php'; ?>