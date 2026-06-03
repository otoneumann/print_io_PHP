<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print.io - PDF Management</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; max-width: 800px; margin: 0 auto; padding: 20px; color: #333; }
        header { border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px; }
        nav a { margin-right: 15px; text-decoration: none; color: #007bff; }
        .flash-message { padding: 10px; margin-bottom: 20px; border-radius: 4px; }
        .flash-message.info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .flash-message.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .flash-message.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        form div { margin-bottom: 15px; }
        label { display: block; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="password"] { width: 100%; padding: 8px; box-sizing: border-box; }
        button { padding: 10px 15px; cursor: pointer; background: #007bff; color: #fff; border: none; border-radius: 4px; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <header>
        <h1>Print.io</h1>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php">Dashboard</a>
                <a href="upload.php">Upload</a>
                <a href="profile.php">Profile</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="index.php">Register</a>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <?php 
        $flash = get_flash_message();
        if ($flash): ?>
            <div class="flash-message <?php echo htmlspecialchars($flash['type']); ?>">
                <?php echo htmlspecialchars($flash['text']); ?>
            </div>
        <?php endif; ?>
