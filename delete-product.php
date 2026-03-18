<?php
session_start();
require 'db.php';

// Protect admin page
if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}

// Check if ID exists
if (!isset($_GET['id'])) {
    header("Location: admin-manage.php");
    exit();
}

$id = intval($_GET['id']);

// Delete product
$query = "DELETE FROM products WHERE product_id = $id";

if ($conn->query($query)) {
    header("Location: admin-manage.php?deleted=1");
    exit();
} else {
    echo "Error deleting product: " . $conn->error;
}
?>