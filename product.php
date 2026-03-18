<?php
session_start();
include 'db.php';
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("No product selected.");
}
$id = (int) $_GET['id'];
$sql    = "SELECT product_id, name, price, quantity, description, image FROM products WHERE product_id = $id";
$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}
if ($result->num_rows == 0) {
    die("Product not found.");
}
$product = $result->fetch_assoc();
$msg     = "";
$msgType = "";
if (isset($_POST['add_to_cart'])) {
    $qty = (int) $_POST['qty'];
    if ($qty < 1) {
        $msg     = "Quantity must be at least 1.";
        $msgType = "error";
    } elseif ($qty > (int) $product['quantity']) {
        $msg     = "Requested quantity exceeds available stock (" . (int)$product['quantity'] . ").";
        $msgType = "error";
    } else {
        $current = isset($_SESSION['cart'][$id]) ? (int)$_SESSION['cart'][$id] : 0;
        $newQty  = $current + $qty;
        if ($newQty > (int) $product['quantity']) {
            $newQty = (int) $product['quantity'];
            $msg    = "Cart updated — maximum available quantity reached.";
        } else {
        }
            $msg = "Added to cart successfully!";
        $_SESSION['cart'][$id] = $newQty;
        $msgType = "success";
    }
}
$cartCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo htmlspecialchars($product['name']); ?> - AUREA</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --cream:   #f8f5f0;
      --ink:     #1a1a18;
      --forest:  #2d4a2d;
      --sage:    #7a9e7e;
      --gold:    #c9a84c;
      --muted:   #6b6b60;
      --border:  #e0dbd0;
      --white:   #ffffff;
      --red:     #c0392b;
      --radius:  18px;
      --shadow:  0 8px 40px rgba(26,26,24,.10);
    }
    html { scroll-behavior: smooth; }
    body {
      font-family: 'Tajawal', sans-serif;
      background: var(--cream);
      color: var(--ink);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    a { color: inherit; text-decoration: none; }
    img { display: block; max-width: 100%; }
    .topbar {
      position: sticky;
      top: 0;
      z-index: 100;
      background: rgba(248,245,240,.92);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid var(--border);
    }
    .topbar__inner {
      max-width: 1100px;
      margin: 0 auto;
      padding: 14px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
    }
    .brand img { height: 44px; }
    .nav {
      display: flex;
      align-items: center;
      gap: 22px;
      flex-wrap: wrap;
    }
    .nav a {
      font-size: .93rem;
      font-weight: 500;
      color: var(--muted);
      transition: color .2s;
    }
    .nav a:hover { color: var(--forest); }
    .nav .cart-link {
      position: relative;
      display: flex;
      align-items: center;
      gap: 6px;
      background: var(--forest);
      color: var(--white);
      padding: 8px 18px;
      border-radius: 999px;
      font-size: .88rem;
      font-weight: 700;
      transition: background .2s, transform .15s;
    }
    .nav .cart-link:hover { background: #1e331e; transform: translateY(-1px); }
    .cart-badge {
      background: var(--gold);
      color: var(--ink);
      font-size: .72rem;
      font-weight: 800;
      border-radius: 999px;
      padding: 1px 7px;
      min-width: 22px;
      text-align: center;
    }
    .product-section {
      flex: 1;
      padding: 60px 24px;
    }
    .container { max-width: 1100px; margin: 0 auto; }
    .product-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 48px;
      align-items: start;
    }
    .product-image-wrap {
      position: relative;
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: var(--shadow);
      background: #e8e3d8;
      aspect-ratio: 4/5;
    }
    .product-image-wrap img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform .5s ease;
    }
    .product-image-wrap:hover img { transform: scale(1.04); }
    .product-image-wrap .badge-img {
      position: absolute;
      top: 16px;
      right: 16px;
      background: var(--forest);
      color: var(--white);
      font-size: .75rem;
      font-weight: 700;
      letter-spacing: .05em;
      text-transform: uppercase;
      padding: 6px 14px;
      border-radius: 999px;
    }
    .product-info { display: flex; flex-direction: column; gap: 20px; }
    .product-category {
      font-size: .8rem;
      font-weight: 700;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: var(--sage);
    }
    .product-title {
      font-family: 'Playfair Display', serif;
      font-size: 2.4rem;
      font-weight: 700;
      line-height: 1.2;
      color: var(--forest);
    }
    .product-desc {
      font-size: 1rem;
      line-height: 1.75;
      color: var(--muted);
      border-right: 3px solid var(--sage);
      padding-right: 14px;
    }
    .product-meta {
      display: flex;
      gap: 24px;
      align-items: center;
    }
      flex-wrap: wrap;
    .price-tag {
      font-family: 'Playfair Display', serif;
      font-size: 2rem;
      font-weight: 700;
      color: var(--forest);
    }
    .price-tag span { font-size: 1rem; font-weight: 400; color: var(--muted); }
    .stock-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #e8f4ea;
      color: var(--forest);
      font-size: .85rem;
      font-weight: 600;
      padding: 6px 14px;
      border-radius: 999px;
    }
    .stock-pill::before {
      content: '';
      width: 8px; height: 8px;
      background: var(--sage);
      border-radius: 50%;
    }
    .stock-pill.low { background: #fef3e2; color: #a0522d; }
    .stock-pill.low::before { background: var(--gold); }
    .stock-pill.out { background: #fdecea; color: var(--red); }
    .stock-pill.out::before { background: var(--red); }
    .cart-form {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 24px;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }
    .qty-row {
      display: flex;
      align-items: center;
      gap: 12px;
    }
      flex-wrap: wrap;
    .qty-label {
      font-size: .9rem;
      font-weight: 600;
      color: var(--ink);
      min-width: 60px;
    }
    .qty-control {
      display: flex;
      align-items: center;
      border: 1.5px solid var(--border);
      border-radius: 10px;
      overflow: hidden;
      background: var(--cream);
    }
    .qty-btn {
      width: 40px; height: 40px;
      background: none;
      border: none;
      font-size: 1.2rem;
      cursor: pointer;
      color: var(--forest);
      font-weight: 700;
      transition: background .15s;
    }
    .qty-btn:hover { background: var(--border); }
    .qty-input {
      width: 56px;
      text-align: center;
      border: none;
      background: none;
      font-family: 'Tajawal', sans-serif;
      font-size: 1rem;
      font-weight: 700;
      color: var(--ink);
      outline: none;
      -moz-appearance: textfield;
    }
    .qty-input::-webkit-outer-spin-button,
    .qty-input::-webkit-inner-spin-button { -webkit-appearance: none; }
    .btn-add {
      width: 100%;
      padding: 14px;
      background: var(--forest);
      color: var(--white);
      border: none;
      border-radius: 12px;
      font-family: 'Tajawal', sans-serif;
      font-size: 1rem;
      font-weight: 700;
      cursor: pointer;
      letter-spacing: .02em;
      transition: background .2s, transform .15s, box-shadow .2s;
      box-shadow: 0 4px 18px rgba(45,74,45,.25);
    }
    .btn-add:hover {
      background: #1e331e;
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(45,74,45,.3);
    }
    .btn-add:active { transform: translateY(0); }
    .btn-add:disabled {
      background: #a0a090;
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }
    .btn-back {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      color: var(--muted);
      font-size: .9rem;
      font-weight: 500;
      transition: color .2s;
    }
    .btn-back:hover { color: var(--forest); }
    .alert {
      border-radius: 10px;
      padding: 12px 16px;
      font-size: .9rem;
      font-weight: 500;
      animation: fadeIn .3s ease;
    }
    .alert.success { background: #e8f4ea; color: #2d6a31; border: 1px solid #b7dfbb; }
    .alert.error   { background: #fdecea; color: var(--red);  border: 1px solid #f5c6c2; }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-6px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .footer {
      background: var(--forest);
      color: rgba(255,255,255,.7);
      padding: 40px 24px;
      margin-top: auto;
    }
    .footer__grid {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr;
      gap: 32px;
    }
    .footer a { color: rgba(255,255,255,.6); font-size: .88rem; transition: color .2s; }
    .footer a:hover { color: var(--white); }
    .footer b  { color: var(--white); font-size: .95rem; }
    @media (max-width: 750px) {
      .product-grid   { grid-template-columns: 1fr; gap: 28px; }
      .product-title  { font-size: 1.8rem; }
      .footer__grid   { grid-template-columns: 1fr; }
      .topbar__inner  { flex-wrap: wrap; }
    }
  </style>
</head>
<body>
<header class="topbar">
  <div class="topbar__inner">
    <a class="brand" href="index.php" aria-label="Home">
      <img src="assets/images/logo.png" alt="AUREA logo">
    </a>
    <nav class="nav" aria-label="Main">
      <a href="index.php">Home</a>
      <a href="index.php#plants">Shop</a>
      <a href="contact.php">Contact</a>
      <a class="cart-link" href="cart.php">
        Cart
        <?php if ($cartCount > 0): ?>
          <span class="cart-badge"><?php echo $cartCount; ?></span>
        <?php endif; ?>
      </a>
    </nav>
  </div>
</header>
<main class="product-section">
  <div class="container">
    <a class="btn-back" href="index.php#plants" style="display:inline-flex; margin-bottom:28px;">
      Back to Shop
    </a>
    <div class="product-grid">
      <div class="product-image-wrap">
        <img
          src="assets/images/<?php echo htmlspecialchars($product['image']); ?>"
          alt="<?php echo htmlspecialchars($product['name']); ?>"
        >
        <span class="badge-img">AUREA</span>
      </div>
      <div class="product-info">
        <p class="product-category">Natural Plants</p>
        <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
        <p class="product-desc"><?php echo htmlspecialchars($product['description']); ?></p>
        <div class="product-meta">
          <div class="price-tag">
            $<?php echo number_format($product['price'], 2); ?>
            <span>/ item</span>
          </div>
          <?php
            $qty = (int) $product['quantity'];
            if ($qty === 0) {
                echo '<span class="stock-pill out">Out of Stock</span>';
            } elseif ($qty <= 5) {
                echo '<span class="stock-pill low">Only ' . $qty . ' left</span>';
            } else {
                echo '<span class="stock-pill">In Stock · ' . $qty . '</span>';
            }
          ?>
        </div>
        <div class="cart-form">
          <?php if ($msg !== ""): ?>
            <div class="alert <?php echo $msgType; ?>"><?php echo htmlspecialchars($msg); ?></div>
          <?php endif; ?>
          <?php if ($qty > 0): ?>
          <form method="POST" id="cartForm">
            <div class="qty-row">
              <label class="qty-label" for="qtyInput">Qty</label>
              <div class="qty-control">
                <button type="button" class="qty-btn" onclick="changeQty(-1)">-</button>
                <input
                  type="number"
                  id="qtyInput"
                  name="qty"
                  value="1"
                  min="1"
                  max="<?php echo $qty; ?>"
                  class="qty-input"
                  required
                  oninput="clampQty(this)"
                >
                <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
              </div>
              <span style="font-size:.8rem; color:var(--muted);">
                Max: <?php echo $qty; ?>
              </span>
            </div>
            <button type="submit" name="add_to_cart" class="btn-add">
              Add to Cart
            </button>
          </form>
          <a href="cart.php" style="text-align:center; color:var(--sage); font-size:.88rem; font-weight:600;">
            View Cart
          </a>
          <?php else: ?>
            <button class="btn-add" disabled>Out of Stock</button>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</main>
<footer class="footer">
  <div class="container footer__grid">
    <div>
      <b>AUREA - Floral & Plants</b>
      <div style="margin-top:8px; font-size:.88rem; line-height:1.7;">
        A modern natural plant store.
      </div>
    </div>
    <div>
      <b>Pages</b>
      <div style="margin-top:10px; display:grid; gap:8px;">
        <a href="index.php">Home</a>
        <a href="index.php#plants">Shop</a>
        <a href="cart.php">Cart</a>
        <a href="contact.php">Contact</a>
      </div>
    </div>
    <div>
      <b>Project</b>
      <div style="margin-top:10px; display:grid; gap:8px;">
        <a href="admin-login.php">Admin</a>
        <a href="checkout.php">Checkout</a>
      </div>
    </div>
  </div>
</footer>
<script>
  const maxQty = <?php echo $qty; ?>;
  function changeQty(delta) {
    const input = document.getElementById('qtyInput');
    let val = parseInt(input.value) || 1;
    val = Math.min(maxQty, Math.max(1, val + delta));
    input.value = val;
  }
  function clampQty(input) {
    let val = parseInt(input.value) || 1;
    if (val < 1)      input.value = 1;
    if (val > maxQty) input.value = maxQty;
  }
</script>
</body>
</html>