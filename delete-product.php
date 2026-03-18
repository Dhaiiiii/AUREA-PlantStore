<?php
session_start();
require 'db.php';

// Protect admin page
if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}

// Get product ID
if (!isset($_GET['id'])) {
    header("Location: admin-manage.php");
    exit();
}

$id = $_GET['id'];

// Fetch product data
$query = "SELECT * FROM products WHERE product_id = $id";
$result = $conn->query($query);
$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

$message = "";

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    // Check if new image uploaded
    if (!empty($_FILES['image']['name'])) {

        $imageName = $_FILES['image']['name'];
        $imageTmp = $_FILES['image']['tmp_name'];
        $targetPath = "assets/images/" . basename($imageName);

        if (move_uploaded_file($imageTmp, $targetPath)) {
            $updateImage = ", image='$imageName'";
        } else {
            $message = "Image upload failed.";
        }

    } else {
        $updateImage = ""; // keep old image
    }

    // Update DB
    $updateQuery = "
        UPDATE products 
        SET name='$name', price='$price', quantity='$quantity' $updateImage
        WHERE product_id=$id
    ";

    if ($conn->query($updateQuery)) {
        header("Location: admin-manage.php?updated=1");
        exit();
    } else {
        $message = "Database error: " . $conn->error;
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Edit Product – AUREA</title>
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

    <h2>Edit Product</h2>
    <p style="color:red;"><?php echo $message; ?></p>

    <form method="POST" enctype="multipart/form-data" onsubmit="return validateEditProduct();">

      <label>Product Name</label>
      <input type="text" name="name" id="name" value="<?php echo $product['name']; ?>">

      <label>Price</label>
      <input type="text" name="price" id="price" value="<?php echo $product['price']; ?>">

      <label>Quantity</label>
      <input type="number" name="quantity" id="quantity" value="<?php echo $product['quantity']; ?>">

      <label>Current Image</label><br>
      <img src="assets/images/<?php echo $product['image']; ?>" width="120" style="border-radius:6px;"><br><br>

      <label>Upload New Image (optional)</label>
      <input type="file" name="image" id="image">

      <button class="btn btn--primary" type="submit">Update Product</button>
    </form>

  </div>
</section>

<footer class="footer">
  <div class="container">© 2026 AUREA – Floral & Plants</div>
</footer>

</body>
</html>