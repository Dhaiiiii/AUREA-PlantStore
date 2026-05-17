
<?php
// Developed by: Dhay
// Task: Create MySQL database connection using PHP

$host = "127.0.0.1";
$user = "root";
$password = "#Dai12345";
$database = "aurea_store";
$port = 3306;

$conn = new mysqli($host, $user, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Returns total number of items in the cart session
function getCartCount()
{
    return isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
}
?>
 