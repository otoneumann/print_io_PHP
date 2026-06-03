<?php
require_once 'init.php';

// If user is not logged in, redirect them to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

    <h1>Welcome, <?php echo htmlspecialchars(strtoupper($_SESSION['username'])); ?>!</h1>
    <p>This is your private dashboard.</p>
    <a href="logout.php">Logout</a><?php
