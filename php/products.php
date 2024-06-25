<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AB | Receipt</title>
    <link rel="stylesheet" href="../css/products_php_styles.css">
</head>
<body id="receipt">

    <div class="header">
        <div id="title">
            <h1>The Arch Bar</h1>
        </div>

        <!--Navigation Bar -->
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
    </div>';

echo '<div class="container">
    <h2>RECEIPT</h2>';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Array of products
    $productNames = [
        "Peking Duck", "Kung Pao Chicken", "Hainanese Chicken Rice",
        "Shredded Pork with Garlic Sauce", "Century Egg and Pork Congee",
        "Truffle Mac n' Cheese", "Steak", "Arch Burger",
        "Roasted Salmon", "Surf n' Turf Fettuccine"
    ];

    // Extract customer information
    $customerInfo = [
        'firstName' => $_POST['firstName'],
        'lastName' => $_POST['lastName'],
        'phone' => $_POST['phone'],
        'email' => $_POST['email'],
        'address' => $_POST['address'],
        'zip' => $_POST['zip'],
        'ccname' => $_POST['ccname'],
        'cctype' => $_POST['cctype'],
        'ccnumber' => $_POST['ccnumber'],
        'maskedCC' => 'xxxx-xxxx-xxxx-' . substr($_POST['ccnumber'], -4),
        'ccexpiration' => $_POST['ccexpiration'],
        'cvv' => $_POST['cvv']
    ];

    // Extract product details and calculate totals
    $total = 0;
    $productDetails = [];
    for ($i = 1; $i <= 10; $i++) {
        if (isset($_POST['quantity' . $i]) && $_POST['quantity' . $i] > 0 && isset($_POST['subtotal' . $i])) {
            $quantity = $_POST['quantity' . $i];
            $subtotal = $_POST['subtotal' . $i];
            $total += floatval($subtotal);
            $productDetails[] = [
                'name' => $productNames[$i - 1], // Use the product name from the array
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
        }
    }

    // Add shipping amount if delivery is chosen
    $shipping = $_POST['shipping'] ?? 'Pickup';
    $shippingAmount = $shipping === 'Delivery' ? 7.00 : 0;
    $total += $shippingAmount;

    // Display customer and order information
    echo '<p>Thank you for your order! We are working on preparing your delicious meal!</p>';
    echo '<p>Date: ' . date("Y-m-d") . '</p>';
    echo '<h2>Customer Information</h2>';
    echo '<p>Name: ' . htmlspecialchars($customerInfo['firstName']) . ' ' . htmlspecialchars($customerInfo['lastName']) . '</p>';
    echo '<p>Phone Number: ' . htmlspecialchars($customerInfo['phone']) . '</p>';
    echo '<p>Email: ' . htmlspecialchars($customerInfo['email']) . '</p>';
    echo '<p>Address: ' . htmlspecialchars($customerInfo['address']) . ', ' . htmlspecialchars($customerInfo['zip']) . '</p>';

    echo '<h2>Order Details</h2>';
    if (!empty($productDetails)) {
        echo '<table class="center-table"><tr><th>Product</th><th>Quantity</th><th>Subtotal</th></tr>';
        foreach ($productDetails as $details) {
            echo '<tr><td>' . htmlspecialchars($details['name']) . '</td><td>' . htmlspecialchars($details['quantity']) . '</td><td>$' . htmlspecialchars($details['subtotal']) . '</td></tr>';
        }
        echo '</table>';
    }

    echo '<h2>Pickup / Delivery Details</h2>';
    if ($shipping === 'Delivery') {
        echo '<p>Delivery Method: Delivery</p>';
        echo '<p>Delivery Amount: $' . $shippingAmount . '</p>';
    } else {
        echo '<p>Pickup (No delivery)</p>';
    }

    echo '<h2>Payment Details</h2>';
    echo '<p>Card Type: ' . htmlspecialchars($customerInfo['cctype']) . '</p>';
    echo '<p>Card Number: ' . htmlspecialchars($customerInfo['maskedCC']) . '</p>';
    echo '<p>Expiration Date: ' . htmlspecialchars($customerInfo['ccexpiration']) . '</p>';

    echo '<h2>Grand Total: $' . $total . '</h2>';

    // Storing data in SQLite database
    try {
        // Create SQLite database connection
        $db = new PDO('sqlite:../store.db');

        // Create table if it doesn't exist
        $db->exec("CREATE TABLE IF NOT EXISTS orders (
            id INTEGER PRIMARY KEY,
            firstName TEXT,
            lastName TEXT,
            phone TEXT,
            email TEXT,
            address TEXT,
            zip TEXT,
            ccname TEXT,
            cctype TEXT,
            maskedCC TEXT,
            ccexpiration TEXT,
            total REAL,
            shipping TEXT,
            date TEXT
        )");

        // Prepare the order data
        $date = date("Y-m-d");

        // Insert order data
        $stmt = $db->prepare("INSERT INTO orders (firstName, lastName, phone, email, address, zip, ccname, cctype, maskedCC, ccexpiration, total, shipping, date) VALUES (:firstName, :lastName, :phone, :email, :address, :zip, :ccname, :cctype, :maskedCC, :ccexpiration, :total, :shipping, :date)");
        $stmt->bindParam(':firstName', $customerInfo['firstName']);
        $stmt->bindParam(':lastName', $customerInfo['lastName']);
        $stmt->bindParam(':phone', $customerInfo['phone']);
        $stmt->bindParam(':email', $customerInfo['email']);
        $stmt->bindParam(':address', $customerInfo['address']);
        $stmt->bindParam(':zip', $customerInfo['zip']);
        $stmt->bindParam(':ccname', $customerInfo['ccname']);
        $stmt->bindParam(':cctype', $customerInfo['cctype']);
        $stmt->bindParam(':maskedCC', $customerInfo['maskedCC']);
        $stmt->bindParam(':ccexpiration', $customerInfo['ccexpiration']);
        $stmt->bindParam(':total', $total);
        $stmt->bindParam(':shipping', $shipping);
        $stmt->bindParam(':date', $date);
        $stmt->execute();

        echo '<h3>You successfully submitted the order</h3>';
        echo "<p>Thank you for choosing The Arch Restaurant!</p>";
    } catch (PDOException $e) {
        echo "<h3>Error writing to database: " . $e->getMessage() . "</h3>";
    }
}

echo '</div>';

echo '<footer>
    <div id="copyright">
        © 2023 The Arch Restaurant
    </div>
</footer>

</body>
</html>';

