<?php
include 'db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("No product selected.");
}

$id = (int) $_GET['id'];

$sql = "SELECT product_id, name, price, quantity, description, image FROM products WHERE product_id = $id";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

if ($result->num_rows == 0) {
    die("Product not found.");
}

$product = $result->fetch_assoc();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?php echo htmlspecialchars($product['name']); ?> – AUREA</title>
  <link rel="stylesheet" href="assets/css/styles.css" />
</head>
<body>

<header class="topbar">
  <div class="container topbar__inner">
    <a class="brand" href="index.php" aria-label="Home">
      <img class="brand__logo" src="assets/images/logo.png" alt="Aurea Floral & Plants logo">
    </a>
    <nav class="nav" aria-label="Main">
      <a href="index.php">Home</a>
      <a href="index.php#plants">Shop</a>
      <a href="product.php?id=<?php echo (int)$product['product_id']; ?>">Product</a>
      <a href="cart.html">Cart</a>
      <a href="contact.html">Contact</a>
      <a class="cta" href="cart.html">Checkout</a>
    </nav>
  </div>
</header>

<section class="section">
  <div class="container">
    <div class="panel" style="display:grid; grid-template-columns: 1fr 1fr; gap:24px; align-items:center;">
      
      <div>
        <img 
          src="assets/images/<?php echo htmlspecialchars($product['image']); ?>" 
          alt="<?php echo htmlspecialchars($product['name']); ?>"
          style="width:100%; border-radius:24px; display:block;"
        >
      </div>

      <div>
        <span class="badge">AUREA Product</span>
        <h1 style="margin-top:12px;"><?php echo htmlspecialchars($product['name']); ?></h1>
        <p style="color: var(--muted); margin-top:10px;">
          <?php echo htmlspecialchars($product['description']); ?>
        </p>

        <div style="margin-top:18px; display:grid; gap:10px;">
          <div><strong>Price:</strong> $<?php echo number_format($product['price'], 2); ?></div>
          <div><strong>Available Quantity:</strong> <?php echo (int)$product['quantity']; ?></div>
        </div>

        <div style="margin-top:22px; display:flex; gap:12px; flex-wrap:wrap;">
          <a class="btn btn--primary" href="cart.html">Add to cart</a>
          <a class="btn btn--ghost" href="index.php#plants">Back to shop</a>
        </div>
      </div>

    </div>
  </div>
</section>

<footer class="footer">
  <div class="container footer__grid">
    <div>
      <b style="color: var(--ink);">AUREA – Floral & Plants</b>
      <div style="margin-top:6px;">Original UI prototype inspired by modern plant store layouts.</div>
    </div>
    <div>
      <b style="color: var(--ink);">Pages</b>
      <div style="margin-top:8px; display:grid; gap:6px;">
        <a href="index.php">Home</a>
        <a href="index.php#plants">Shop</a>
        <a href="cart.html">Cart</a>
        <a href="contact.html">Contact</a>
      </div>
    </div>
    <div>
      <b style="color: var(--ink);">Project</b>
      <div style="margin-top:8px; display:grid; gap:6px;">
        <a href="admin-login.html">Admin (UI)</a>
        <a href="checkout.html">Checkout (UI)</a>
      </div>
    </div>
  </div>
</footer>

</body>
</html>