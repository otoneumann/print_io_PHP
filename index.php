<?php
require_once 'init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = str_replace(' ', '', trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $plain = $_POST['password'];
    $hash = password_hash($plain, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, password_plain) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $hash, $plain]);

        // Registration complete: redirect directly to login
        header("Location: login.php");
        exit;
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    }
}
?>

<form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Register</button>
</form>