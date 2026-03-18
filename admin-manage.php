<?php
session_start();
require 'db.php';

// Protect admin page
if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}

// Search logic
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM products 
              WHERE name LIKE '%$search%' 
              OR product_id LIKE '%$search%'";
} else {
    $query = "SELECT * FROM products";
}

$result = $conn->query($query);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>AUREA – Manage Products</title>
  <link rel="stylesheet" href="assets/css/styles.css" />

  <style>
    /* Compact, clean table styling */
    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        font-size: 15px;
    }

    th, td {
        padding: 8px 12px;
        text-align: left;
        border-bottom: 1px solid #e5e5e5;
        vertical-align: middle;
    }

    th {
        background: #f7f7f7;
        font-weight: 600;
    }

    tr:hover {
        background: #fafafa;
    }

    img {
        border-radius: 6px;
        width: 60px;
        height: auto;
        object-fit: cover;
    }

    .actions a {
        margin-right: 6px;
    }
  </style>
</head>
<body>

<header class="topbar">
  <div class="container topbar__inner">
    <a class="brand" href="index.php" aria-label="Home">
      <img class="brand__logo" src="assets/images/logo.png" alt="Aurea Floral & Plants logo">
    </a>
    <nav class="nav" aria-label="Main">
      <a href="index.php">Home</a>
      <a href="#plants">Shop</a>
      <a href="product.php">Product</a>
      <a href="cart.html">Cart</a>
      <a href="contact.php">Contact</a>
      <a class="cta" href="cart.html">Checkout</a>
    </nav>
  </div> 
</header>

<section class="section">
  <div class="container">
    <div class="section__head" style="margin-top:0">
      <div>
        <h2>Manage products</h2>
        <p>CRUD operations implemented with PHP + MySQL.</p>
      </div>
      <a href="logout.php" class="btn btn--primary">Logout</a>
    </div>

    <!-- Search Bar -->
    <form method="GET" style="margin-bottom:20px;">
      <input type="text" name="search" placeholder="Search by name or ID" value="<?php echo $search; ?>">
      <button class="btn btn--primary" type="submit">Search</button>
    </form>

    <!-- Add Product -->
    <div class="panel">
      <b style="color: var(--sage-700);">Add new product</b>
      <p style="color: var(--muted); margin-top:6px;">Name, price, stock, image…</p>
      <div style="margin-top:10px; display:flex; gap:10px; flex-wrap:wrap;">
        <a class="btn btn--primary" href="add-product.php">Add</a>
      </div>
    </div>

    <!-- Product List -->
    <h3 style="margin-top:30px;">All Products</h3>

    <table>
      <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Name</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Actions</th>
      </tr>

      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?php echo $row['product_id']; ?></td>

        <?php 
        $imageFile = basename($row['image']); 
        ?>
        <td><img src="assets/images/<?php echo $imageFile; ?>"></td>

        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['price']; ?></td>
        <td><?php echo $row['quantity']; ?></td>

        <td class="actions">
          <a class="btn btn--primary" href="edit-product.php?id=<?php echo $row['product_id']; ?>">Edit</a>
          <a class="btn btn--primary" style="background:#d9534f;"
             href="delete-product.php?id=<?php echo $row['product_id']; ?>"
             onclick="return confirm('Delete this product?')">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </table>

  </div>
</section>

<footer class="footer">
  <div class="container">© 2026 AUREA – Floral & Plants</div>
</footer>

<script src="assets/js/main.js"></script>
</body>
</html>