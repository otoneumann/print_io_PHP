<?php
require_once 'init.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $username = str_replace(' ', '', trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $plain = $_POST['password'];

    $errors = [];

    if (strlen($username) < 3) $errors[] = "Username must be at least 3 characters.";
    if (!$email) $errors[] = "Invalid email format.";
    if (strlen($plain) < 6) $errors[] = "Password must be at least 6 characters.";

    if (empty($errors)) {
        // Check for duplicates
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errors[] = "Username or Email already exists.";
        }
    }

    if (empty($errors)) {
        $hash = password_hash($plain, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hash]);

            set_flash_message("Registration successful! Please login.", "success");
            header("Location: login.php");
            exit;
        } catch (PDOException $e) {
            set_flash_message("Database Error: " . $e->getMessage(), "error");
        }
    } else {
        set_flash_message(implode(" ", $errors), "error");
    }
}

include 'header.php';
?>

<h2>Register</h2>
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <div>
        <label>Username</label>
        <input type="text" name="username" placeholder="Username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
    </div>
    <div>
        <label>Email</label>
        <input type="email" name="email" placeholder="Email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
    </div>
    <div>
        <label>Password</label>
        <input type="password" name="password" placeholder="Password" required>
    </div>
    <button type="submit">Register</button>
</form>
<p>Already have an account? <a href="login.php">Login here</a></p>

<?php include 'footer.php'; ?>