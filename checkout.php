<?php
session_start();
include 'db.php';


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['place_order']) && $_GET['place_order'] == 1) {
    $_SESSION['cart'] = [];
    header("Location: index.php?order=success");
    exit;
}

$cartCount = array_sum($_SESSION['cart']);
$cartItems = [];
$subtotal = 0;
$delivery = 6;
$discount = 0;

if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $ids = array_map('intval', $ids);

    if (!empty($ids)) {
        $idList = implode(',', $ids);
        $sql = "SELECT product_id, name, price, quantity, image FROM products WHERE product_id IN ($idList)";
        $result = $conn->query($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $productId = (int) $row['product_id'];
                $qtyInCart = isset($_SESSION['cart'][$productId]) ? (int) $_SESSION['cart'][$productId] : 0;
                $availableStock = (int) $row['quantity'];

                if ($qtyInCart > $availableStock) {
                    $qtyInCart = $availableStock;
                    $_SESSION['cart'][$productId] = $qtyInCart;
                }

                if ($qtyInCart <= 0) {
                    unset($_SESSION['cart'][$productId]);
                    continue;
                }

                $itemTotal = $qtyInCart * (float) $row['price'];
                $subtotal += $itemTotal;

                $cartItems[] = [
                    'product_id' => $productId,
                    'name' => $row['name'],
                    'price' => (float) $row['price'],
                    'image' => $row['image'],
                    'qty' => $qtyInCart,
                    'item_total' => $itemTotal
                ];
            }
        }
    }
}

if (empty($cartItems)) {
    $delivery = 0;
}

$total = $subtotal + $delivery - $discount;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>AUREA – Checkout</title>
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
      <a href="cart.php" style="display:flex; align-items:center; gap:6px;"><img src="assets/images/cart_logo.png" alt="Cart" style="width:24px; height:24px;"> Cart <?php if ($cartCount > 0) echo '(' . $cartCount . ')'; ?></a>
      <a href="contact.php">Contact</a>
      <a class="cta" href="checkout.php">Checkout</a>
      <a class="btn btn--secondary" href="help.php">Help</a>
    </nav>
  </div>
</header>

<section class="section">
  <div class="container split" style="grid-template-columns: 1fr .85fr;">
    
    <div class="panel">
      <h2 style="color: var(--sage-700);">Checkout</h2>
      <p style="color: var(--muted); margin-top:6px;">
        Complete your order details below.
      </p>

      <?php if (empty($cartItems)): ?>
        <div style="margin-top:18px; padding:16px; border-radius:14px; background:#fff; border:1px solid rgba(220,234,225,.95);">
          <h3>Your cart is empty</h3>
          <p style="color: var(--muted); margin-top:8px;">Please add products before checkout.</p>
          <a class="btn btn--primary" style="margin-top:14px;" href="index.php#plants">Go to shop</a>
        </div>
      <?php else: ?>
        <div style="margin-top:14px; display:grid; gap:10px;">
          <div class="feature">
            <div class="icon">📍</div>
            <div>
              <h3>Shipping address</h3>
              <p>Simple checkout form for the project prototype.</p>
            </div>
          </div>

          <div style="display:grid; gap:10px;">
            <input style="padding:12px 14px; border-radius:14px; border:1px solid rgba(220,234,225,.95); background: rgba(255,255,255,.9)" placeholder="Full name">
            <input style="padding:12px 14px; border-radius:14px; border:1px solid rgba(220,234,225,.95); background: rgba(255,255,255,.9)" placeholder="Phone">
            <input style="padding:12px 14px; border-radius:14px; border:1px solid rgba(220,234,225,.95); background: rgba(255,255,255,.9)" placeholder="City, Street">
          </div>

          <a class="btn btn--ghost" style="margin-top:10px;" href="cart.php">Back to cart</a>
        </div>
      <?php endif; ?>
    </div>

    <div class="panel">
      <b style="color: var(--sage-700);">Summary</b>

      <?php if (empty($cartItems)): ?>
        <div style="margin-top:10px; color: var(--muted); font-weight:650;">
          No items in cart
        </div>
      <?php else: ?>
        <div style="margin-top:12px; display:grid; gap:12px;">
          <?php foreach ($cartItems as $item): ?>
            <div style="padding-bottom:10px; border-bottom:1px solid rgba(220,234,225,.95);">
              <div style="font-weight:800;">
                <?php echo htmlspecialchars($item['name']); ?> × <?php echo $item['qty']; ?>
              </div>
              <div style="margin-top:4px; color: var(--muted); font-size:14px;">
                Unit price: $<?php echo number_format($item['price'], 2); ?>
              </div>
              <div style="margin-top:4px; color: var(--muted); font-size:14px;">
                Subtotal: $<?php echo number_format($item['item_total'], 2); ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div style="margin-top:14px; display:grid; gap:8px; color: var(--muted); font-weight:650;">
          <div style="display:flex; justify-content:space-between;">
            <span>Subtotal</span>
            <span>$<?php echo number_format($subtotal, 2); ?></span>
          </div>

          <div style="display:flex; justify-content:space-between;">
            <span>Delivery</span>
            <span>$<?php echo number_format($delivery, 2); ?></span>
          </div>

          <div style="display:flex; justify-content:space-between;">
            <span>Discount</span>
            <span>-$<?php echo number_format($discount, 2); ?></span>
          </div>
        </div>

        <div style="margin-top:12px; display:flex; justify-content:space-between; font-weight:900;">
          <span>Total</span>
          <span>$<?php echo number_format($total, 2); ?></span>
        </div>

        <a class="btn btn--primary" style="margin-top:12px; width:100%;" href="checkout.php?place_order=1">
          Place order
        </a>

        <div style="margin-top:10px; color: var(--muted); font-size: 12px;">
          Prototype only. No payment is processed.
        </div>
      <?php endif; ?>
    </div>

  </div>
</section>

<footer class="footer">
  <div class="container">© 2026 AUREA – Floral & Plants</div>
</footer>

<script src="assets/js/main.js"></script>
</body>
</html>