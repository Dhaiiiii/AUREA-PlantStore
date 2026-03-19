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

    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $quantity = trim($_POST['quantity']);
    $description = trim($_POST['description']);

    if ($name == "" || $price == "" || $quantity == "" || $description == "") {
        $message = "All fields are required.";
    } elseif (!is_numeric($price)) {
        $message = "Price must be a number.";
    } elseif ($quantity < 0) {
        $message = "Quantity cannot be less than 0.";
    } elseif (empty($_FILES['image']['name'])) {
        $message = "Please upload an image.";
    } else {

        // Handle image upload
        $imageName = $_FILES['image']['name'];
        $imageTmp = $_FILES['image']['tmp_name'];
        $targetPath = "assets/images/" . basename($imageName);

        if (move_uploaded_file($imageTmp, $targetPath)) {

            // Insert into DB
            $query = "
                INSERT INTO products (name, price, quantity, description, image)
                VALUES ('$name', '$price', '$quantity', '$description', '$imageName')
            ";

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
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Add Product – AUREA</title>
  <link rel="stylesheet" href="assets/css/styles.css" />
  <script src="validation.js"></script> <!--validation-->

  <style>
    .form-label {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
      color: var(--sage-700);
    }

    .form-input {
      display: block;
      width: 100%;
      padding: 10px;
      margin-bottom: 18px;
      border: 1px solid #dceae1;
      border-radius: 6px;
      background: #fff;
    }

    .panel {
      padding: 25px;
      border-radius: 10px;
      background: #fff;
      border: 1px solid #e5efe8;
      max-width: 900px;
      margin: 0 0 60px 0;
    }

    .section {
      padding-bottom: 80px;
    }
  </style>
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

    <div class="section__head" style="margin-top:0">
      <div>
        <h2>Add New Product</h2>
        <p>Fill in the details below to add a new product.</p>
      </div>
    </div>

    <div class="panel">

      <p style="color:red;"><?php echo $message; ?></p>

      <form method="POST" enctype="multipart/form-data" onsubmit="return validateAddProduct();">

        <label class="form-label">Product Name</label>
        <input type="text" class="form-input" name="name" id="name">

        <label class="form-label">Price</label>
        <input type="text" class="form-input" name="price" id="price">

        <label class="form-label">Quantity</label>
        <input type="number" class="form-input" min="0" name="quantity" id="quantity">

        <label class="form-label">Description</label>
        <textarea class="form-input" rows="4" name="description" id="description"></textarea>

        <label class="form-label">Upload Image</label>
        <input type="file" class="form-input" name="image" id="image">

        <div style="display:flex; gap:10px; margin-top:20px;">
          <button class="btn btn--primary" type="submit">Add Product</button>
          <a class="btn btn--ghost" href="admin-manage.php">Cancel</a>
        </div>

      </form>

    </div>

  </div>
</section>

<footer class="footer">
  <div class="container">© 2026 AUREA – Floral & Plants</div>
</footer>

</body>
</html>