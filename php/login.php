<?php
session_start(); // Start the session

$welcomeMessage = '';
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    try {
        // Create SQLite database connection
        $db = new PDO('sqlite:../store.db');

        // Query user data
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            // If login is successful, set welcome message
            $_SESSION['welcomeMessage'] = "Welcome to your site, $username!";
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['errorMessage'] = "Incorrect username or password";
        }
    } catch (PDOException $e) {
        $_SESSION['errorMessage'] = "Error querying database: " . $e->getMessage();
    }

    // Redirect to the same page to display the error or welcome message
    header("Location: login.php");
    exit();
}

// Retrieve the welcome or error message from the session
if (isset($_SESSION['welcomeMessage'])) {
    $welcomeMessage = $_SESSION['welcomeMessage'];
    unset($_SESSION['welcomeMessage']); // Clear the welcome message from the session
}
if (isset($_SESSION['errorMessage'])) {
    $errorMessage = $_SESSION['errorMessage'];
    unset($_SESSION['errorMessage']); // Clear the error message from the session
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Login Application</title>
    <link rel="stylesheet" href="../css/login_styles.css">
</head>
<body>

<div class="header">
    <div id="title">
        <h1>The Arch Bar</h1>
    </div>

    <!-- Navigation Bar -->
    <nav>
        <ul>
            <a href="../index.html"><li>Home</li></a>
            <a href="../contact.html"><li>Contact</li></a>
            <a href="../products.html"><li>Order Foods</li></a>
            <a href="../search.html"><li>Search</li></a>
            <a href="../signup.html"><li>Sign up</li></a>
            <a href="../login.html"><li>Log in</li></a>
            <a href="../gallery.html"><li>Gallery</li></a>
            <a href="../game.html"><li>Game</li></a>
        </ul>
    </nav>
</div>

<div class="container">
    <?php if ($welcomeMessage): ?>
        <h2><?php echo htmlspecialchars($welcomeMessage); ?></h2>
    <?php else: ?>
        <h1>Log In</h1>
        <?php if ($errorMessage): ?>
            <p style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>
        <form method="post" action="login.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <input type="submit" value="Log In">
            <input type="reset" value="Reset">
        </form>
    <?php endif; ?>
</div>

<footer>
    <div id="copyright">
        © 2023 The Arch Restaurant
    </div>
</footer>

</body>
</html>
