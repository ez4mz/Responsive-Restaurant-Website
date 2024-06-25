<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Create SQLite database connection
    $db = new PDO('sqlite:../store.db');

    // Create products table if it doesn't exist
    $db->exec("CREATE TABLE IF NOT EXISTS products (
        id INTEGER PRIMARY KEY,
        name TEXT,
        price REAL,
        description TEXT,
        quantity INTEGER
    )");

    // Insert products data if the table is empty
    $count = $db->query("SELECT COUNT(*) FROM products")->fetchColumn();
    if ($count == 0) {
        $products = [
            ["Peking Duck", 30, "A classic Chinese delicacy featuring succulent duck, served with thinly sliced carrots, cucumbers, and soft pancakes for a perfect wrap", 10],
            ["Kung Pao Chicken", 20, "A spicy, stir-fried Chinese dish made with tender chicken, peanuts, vegetables, and chili peppers, paired with fluffy white rice", 20],
            ["Hainanese Chicken Rice", 15, "Consists of poached chicken and seasoned rice, served with chili sauce and usually accompanied by a bowl of clear chicken broth", 25],
            ["Shredded Pork with Garlic sauce", 18, "Savory and garlicky shredded pork, a delightful combination of flavors and textures that tantalize the taste buds", 25],
            ["Century Egg and Pork Congee", 15, "A comforting Chinese rice porridge mixed with unique century eggs and tender pork, offering a rich and hearty meal", 30],
            ["Truffle Mac n' Cheese", 17, "A luxurious twist on the classic, featuring creamy cheese sauce infused with truffle oil, making it a rich and decadent treat", 20],
            ["Steak", 33, "A perfectly grilled steak paired with golden, crispy fries - a timeless combination for meat lovers", 10],
            ["Arch Burger", 20, "A juicy burger topped with melted cheese and caramelized onions, served with crispy fries and a tangy pickle on the side", 30],
            ["Roasted Salmon", 22, "Exquisitely roasted salmon, accompanied by a medley of roasted vegetables and a smooth aioli, creating a harmony of flavors", 15],
            ["Surf n' Turf Fettuccine", 18, "A spicy and savory blend of shrimp and sausage with fettuccine, tossed in a Cajun-inspired sauce for a bold flavor fusion", 20]
        ];

        $stmt = $db->prepare("INSERT INTO products (name, price, description, quantity) VALUES (:name, :price, :description, :quantity)");
        foreach ($products as $product) {
            $stmt->bindParam(':name', $product[0]);
            $stmt->bindParam(':price', $product[1]);
            $stmt->bindParam(':description', $product[2]);
            $stmt->bindParam(':quantity', $product[3]);
            $stmt->execute();
        }
    }

    echo "Database created and products inserted successfully.<br>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/search_style.css">
    <title>Product Search Results</title>
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
    </div>';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $keyword = strtolower($_POST["keyword"]);
    $found = false;

    try {
        // Search for products matching the keyword
        $stmt = $db->prepare("SELECT * FROM products WHERE LOWER(name) LIKE :keyword OR LOWER(description) LIKE :keyword");
        $stmt->bindValue(':keyword', '%' . $keyword . '%');
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo '<div class="container">';
        if (count($results) > 0) {
            foreach ($results as $product) {
                echo "<p style='font-size: 1.2em; font-weight: bold; margin: 10px 0;'>Product Name: " . htmlspecialchars($product['name']) . "</p>";
                echo "<p>Price: $" . htmlspecialchars($product['price']) . "</p>";
                echo "<p>Description: " . htmlspecialchars($product['description']) . "</p>";
                echo "<p>Quantity Available: " . htmlspecialchars($product['quantity']) . "</p><br>";
                $found = true;
            }
        } else {
            echo "<p>We don’t have products matching this keyword in our shop.</p>";
        }
        echo '</div>';
    } catch (PDOException $e) {
        echo "<p>Error querying database: " . $e->getMessage() . "</p>";
    }
}

echo '<footer>
    <div id="copyright">
        © 2023 The Arch Restaurant
    </div>
</footer>
</body>
</html>';