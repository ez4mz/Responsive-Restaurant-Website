<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $date = $_POST['date'];
    $foodRating = $_POST["foodRating"];
    $serviceRating = $_POST["serviceRating"];
    $message = $_POST['message'];

    try {
        // Create SQLite database connection
        $db = new PDO('sqlite:../store.db');

        // Create table if it doesn't exist
        $db->exec("CREATE TABLE IF NOT EXISTS contact_form (
            id INTEGER PRIMARY KEY,
            name TEXT,
            email TEXT,
            date TEXT,
            foodRating INTEGER,
            serviceRating INTEGER,
            message TEXT
        )");

        // Insert form data into the database
        $stmt = $db->prepare("INSERT INTO contact_form (name, email, date, foodRating, serviceRating, message) VALUES (:name, :email, :date, :foodRating, :serviceRating, :message)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':foodRating', $foodRating);
        $stmt->bindParam(':serviceRating', $serviceRating);
        $stmt->bindParam(':message', $message);
        $stmt->execute();

        $output = "<h2>Form Submitted Successfully!</h2>";
    } catch (PDOException $e) {
        $output = "<h2>Error writing to database: " . $e->getMessage() . "</h2>";
    }

    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="../css/contact_styles.css">
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
        <h1>Contact Us</h1>
        <h2>We need Your Feedback</h2>

        
        <div class="container">
            ' . $output . '
            <p>Thank you for sending us message!</p>
            <p>We will carefully review your precious advice and respond as soon as possible!</p>
        </div>

        <br>
        <div class="contact-info">
            <h2>Our Information</h2>
            <p>Telephone: +1 (555) 123-4567</p>
            <p>Address: 225 Sullivan St, New York, NY 10012</p>
            <p>Operating Hours: Monday to Saturday, 9:00 AM - 10:00 PM</p>
        </div>
        <section id="map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d755.8689023845178!2d-73.99996145036174!3d40.729559043672246!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259d4955cac01%3A0x58f4b4fcad58588f!2sAll&#39;Antico%20Vinaio!5e0!3m2!1sen!2sus!4v1701716337224!5m2!1sen!2sus" width="400" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </section>
       </div>
    </body>
    <footer>
     
        <div id="copyright">
            © 2023 The Arch Restaurant
        </div>

    </footer>

</html>';
}
?>
