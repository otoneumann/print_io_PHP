<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = str_replace(' ', '', trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $plain = $_POST['password']; // DEVELOPMENT ONLY
    $hash = password_hash($plain, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, password_plain) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $hash, $plain]);
        echo "Registration successful! for user " . strtoupper($username) . ' Yuhuuuuuu';
    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo "Registration failed booo. Please try again.";
    }
}
?>

<form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Register</button>
</form>