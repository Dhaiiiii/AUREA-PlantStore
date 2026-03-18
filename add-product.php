<?php
session_start();
require 'db.php';

// Protect admin page
if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    // Handle image upload
    $imageName = $_FILES['image']['name'];
    $imageTmp = $_FILES['image']['tmp_name'];

    // Save image to assets/images/
    $targetPath = "assets/images/" . basename($imageName);

    if (move_uploaded_file($imageTmp, $targetPath)) {

        // Insert into database
        $query = "INSERT INTO products (name, price, quantity, image)
                  VALUES ('$name', '$price', '$quantity', '$imageName')";

        if ($conn->query($query)) {
            header("Location: admin-manage.php?added=1");
            exit();
        } else {
            $message = "Database error: " . $conn->error;
        }

    } else {
        $message = "Image upload failed.";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Add Product – AUREA</title>
  <link rel="stylesheet" href="assets/css/styles.css" />
  <script src="validation.js"></script>
</head>

<body>

<header class="topbar">
  <div class="container topbar__inner">
    <a class="brand" href="index.php">
      <img class="brand__logo" src="assets/images/logo.png" alt="Aurea Floral & Plants logo">
    </a>
    <nav class="nav">
      <a href="admin-manage.php">Back to Admin</a>
    </nav>
  </div>
</header>

<section class="section">
  <div class="container">

    <h2>Add New Product</h2>
    <p style="color:red;"><?php echo $message; ?></p>

    <form method="POST" enctype="multipart/form-data" onsubmit="return validateAddProduct();">

      <label>Product Name</label>
      <input type="text" name="name" id="name">

      <label>Price</label>
      <input type="text" name="price" id="price">

      <label>Quantity</label>
      <input type="number" name="quantity" id="quantity">

      <label>Product Image</label>
      <input type="file" name="image" id="image">

      <button class="btn btn--primary" type="submit">Add Product</button>
    </form>

  </div>
</section>

<footer class="footer">
  <div class="container">© 2026 AUREA – Floral & Plants</div>
</footer>

</body>
</html>