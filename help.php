<?php
// help.php
session_start();
include 'db.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>AUREA – Help</title>
  <link rel="stylesheet" href="assets/css/styles.css">
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
      <a href="cart.php" style="display:flex; align-items:center; gap:6px;">
        <img src="assets/images/cart_logo.png" alt="Cart" style="width:24px; height:24px;">
        Cart <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) echo '(' . array_sum($_SESSION['cart']) . ')'; ?>
      </a>
      <a href="contact.php">Contact</a>
      <a class="cta" href="checkout.php">Checkout</a>
      <a class="btn btn--secondary" href="help.php">Help</a>
    </nav>
  </div>
</header>

<section class="section">
  <div class="container">
    <div class="section__head">
      <h2>Need Help?</h2>
      <p>If you have any questions or need assistance, please contact us:</p>
      <p>Email: <a href="mailto:support@aurea.com">support@aurea.com</a></p>
    </div>
  </div>
</section>

<footer class="footer">
  <div class="container">©️ 2026 AUREA – Floral & Plants</div>
</footer>

</body>
</html>