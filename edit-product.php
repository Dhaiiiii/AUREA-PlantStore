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
    } else {

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
            $updateImage = "";
        }

        if ($message == "") {
            $updateQuery = "
                UPDATE products 
                SET name='$name', price='$price', quantity='$quantity', description='$description' $updateImage
                WHERE product_id=$id
            ";

            if ($conn->query($updateQuery)) {
                header("Location: admin-manage.php?updated=1");
                exit();
            } else {
                $message = "Database error: " . $conn->error;
            }
        }
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

    /* ⭐ ONLY CHANGE: wider panel + left aligned */
    .panel {
      padding: 25px;
      border-radius: 10px;
      background: #fff;
      border: 1px solid #e5efe8;
      max-width: 900px;      /* doubled width */
      margin: 0 0 60px 0;     /* left aligned, bottom spacing */
    }

    .section {
      padding-bottom: 80px;  /* prevents footer overlap */
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
        <h2>Edit Product</h2>
        <p>Modify product details below.</p>
      </div>
    </div>

    <div class="panel">

      <p style="color:red;"><?php echo $message; ?></p>

      <form method="POST" enctype="multipart/form-data">

        <label class="form-label">Product Name</label>
        <input type="text" class="form-input" name="name" value="<?php echo $product['name']; ?>">

        <label class="form-label">Price</label>
        <input type="text" class="form-input" name="price" value="<?php echo $product['price']; ?>">

        <label class="form-label">Quantity</label>
        <input type="number" class="form-input" min="0" name="quantity" value="<?php echo $product['quantity']; ?>">

        <label class="form-label">Description</label>
        <textarea class="form-input" rows="4" name="description"><?php echo $product['description']; ?></textarea>

        <label class="form-label">Current Image</label>
        <img 
          src="assets/images/<?php echo $product['image']; ?>" 
          width="260"
          style="border-radius:6px; margin:15px 0; display:block;"
        >

        <label class="form-label">Upload New Image (optional)</label>
        <input type="file" class="form-input" name="image">

        <div style="display:flex; gap:10px; margin-top:20px;">
          <button class="btn btn--primary" type="submit">Update Product</button>
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