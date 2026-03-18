<?php
session_start();
require 'db.php'; 

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = "Please enter both username and password.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $_SESSION['admin'] = $username;
            header("Location: admin-manage.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>AUREA – Admin Login</title>
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
      <a href="#plants">Shop</a>
      <a href="product.php">Product</a>
      <a href="cart.php">Cart</a>
      <a href="contact.php">Contact</a>
      <a class="cta" href="cart.php">Checkout</a>
    </nav>
  </div>
</header>

<section class="section">
  <div class="container" style="max-width: 680px;">
    <div class="panel">
      <h2 style="color: var(--sage-700);">Admin login</h2>

      <?php if ($error): ?>
        <p style="color:red; margin-top:10px;"><?php echo $error; ?></p>
      <?php endif; ?>

      <form method="POST" action="admin-login.php" style="margin-top:14px; display:grid; gap:10px;">
        <input 
          name="username"
          style="padding:12px 14px; border-radius:14px; border:1px solid rgba(220,234,225,.95); background: rgba(255,255,255,.9)" 
          placeholder="Manager ID">

        <input 
          type="password" 
          name="password"
          style="padding:12px 14px; border-radius:14px; border:1px solid rgba(220,234,225,.95); background: rgba(255,255,255,.9)" 
          placeholder="Password">

        <button class="btn btn--primary" type="submit">Login</button>
      </form>

      <a class="btn btn--ghost" 
         style="color: var(--ink); border-color: rgba(220,234,225,.95); background: rgba(255,255,255,.78); margin-top:10px;" 
         href="index.php">Back</a>

    </div>
  </div>
</section>

<footer class="footer"><div class="container">© 2026 AUREA – Floral & Plants</div></footer>

<script src="assets/js/main.js"></script>
</body>
</html>