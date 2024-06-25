<?php
session_start(); // Start the session

$errorMessage = '';
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password

    try {
        // Create SQLite database connection
        $db = new PDO('sqlite:../store.db');

        // Create table if it doesn't exist
        $db->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY,
            username TEXT UNIQUE,
            password TEXT
        )");

        // Insert user data
        $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        $_SESSION['successMessage'] = "Registration successful. Thank you for signing up, $username!";
        header("Location: signup.php");
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Handle unique constraint violation
            $_SESSION['errorMessage'] = "Error: Username or email already exists";
        } else {
            $_SESSION['errorMessage'] = "Error writing to database: " . $e->getMessage();
        }
        header("Location: signup.php");
        exit();
    }
}

// Retrieve the error or success message from the session
if (isset($_SESSION['errorMessage'])) {
    $errorMessage = $_SESSION['errorMessage'];
    unset($_SESSION['errorMessage']); // Clear the error message from the session
}
if (isset($_SESSION['successMessage'])) {
    $successMessage = $_SESSION['successMessage'];
    unset($_SESSION['successMessage']); // Clear the success message from the session
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Signup</title>
    <link rel="stylesheet" href="../css/signup_styles.css">
</head>
<body>

<div class="header">
    <div id="title">
        <h1>The Arch Bar</h1>
    </div>

    <!--Navigation Bar -->
    <nav>
        <ul>
            <a href="../index.html"><li>Home</li></a>
            <a href="../contact.html"><li>Contact</li></a>
            <a href="../products.html"><li>Order Online</li></a>
            <a href="../search.html"><li>Search</li></a>
            <a href="../signup.html"><li>Sign up</li></a>
            <a href="../login.html"><li>Log in</li></a>
            <a href="../gallery.html"><li>Gallery</li></a>
            <a href="../game.html"><li>Game</li></a>
        </ul>
    </nav>
</div>

<div class="container">
    <h1>User Registration</h1>

    <?php if ($errorMessage): ?>
        <p style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></p>
    <?php endif; ?>
    <?php if ($successMessage): ?>
        <p style="color: green;"><?php echo htmlspecialchars($successMessage); ?></p>
    <?php endif; ?>

    <form action="signup.php" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <div class="buttons">
            <input type="submit" value="Sign up">
            <input type="reset" value="Reset">
        </div>
    </form>
</div>

<footer>
    <div id="copyright">
        © 2023 The Arch Restaurant
    </div>
</footer>

</body>
</html>
