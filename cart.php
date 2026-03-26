<?php
session_start();
include 'db.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* actions */
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if (($action === 'increase' || $action === 'decrease' || $action === 'remove') && isset($_GET['id'])) {
        $id = (int) $_GET['id'];

        if ($action === 'remove') {
            unset($_SESSION['cart'][$id]);
        } else {
            $sql = "SELECT quantity FROM products WHERE product_id = $id";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                $product = $result->fetch_assoc();
                $stock = (int) $product['quantity'];

                if (isset($_SESSION['cart'][$id])) {
                    if ($action === 'increase') {
                        if ($_SESSION['cart'][$id] < $stock) {
                            $_SESSION['cart'][$id]++;
                        }
                    }

                    if ($action === 'decrease') {
                        $_SESSION['cart'][$id]--;
                        if ($_SESSION['cart'][$id] <= 0) {
                            unset($_SESSION['cart'][$id]);
                        }
                    }
                }
            }
        }

        header("Location: cart.php");
        exit;
    }

    if ($action === 'clear') {
        $_SESSION['cart'] = [];
        header("Location: cart.php");
        exit;
    }
}

$cartCount = array_sum($_SESSION['cart']);
$subtotal = 0;
$delivery = 6;
$discount = 0;
$cartItems = [];

if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $ids = array_map('intval', $ids);
    $idList = implode(',', $ids);

    $sql = "SELECT product_id, name, price, quantity, image FROM products WHERE product_id IN ($idList)";
    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $productId = (int) $row['product_id'];
            $qtyInCart = (int) $_SESSION['cart'][$productId];
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
                'stock' => $availableStock,
                'qty' => $qtyInCart,
                'item_total' => $itemTotal
            ];
        }
    }
}

$total = $subtotal + $delivery - $discount;
if (empty($cartItems)) {
    $delivery = 0;
    $total = 0;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>AUREA – Cart</title>
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
      <a href="cart.php">Cart <?php if ($cartCount > 0) echo '(' . $cartCount . ')'; ?></a>
      <a href="contact.php">Contact</a>
      <a class="cta" href="checkout.php">Checkout</a>
    </nav>
  </div>
</header>

<section class="section">
  <div class="container">
    <div class="section__head" style="margin-top:0">
      <div>
        <h2>Your cart</h2>
        <p>Review your selected items before checkout.</p>
      </div>

      <?php if (!empty($cartItems)): ?>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
          <a class="btn btn--ghost" href="cart.php?action=clear">Delete all</a>
          <a class="btn btn--primary" href="checkout.php">Go to checkout</a>
        </div>
      <?php endif; ?>
    </div>

    <?php if (empty($cartItems)): ?>
      <div class="panel" style="text-align:center;">
        <h3>Your cart is empty</h3>
        <p style="margin-top:8px;">Add some plants first.</p>
        <a class="btn btn--primary" style="margin-top:16px;" href="index.php#plants">Continue shopping</a>
      </div>
    <?php else: ?>
      <div class="split" style="grid-template-columns: 1.3fr .7fr;">
        <div style="display:grid; gap:18px;">
          <?php foreach ($cartItems as $item): ?>
            <div class="panel">
              <div style="display:flex; gap:14px; align-items:center; flex-wrap:wrap;">
                <img
                  src="assets/images/<?php echo htmlspecialchars($item['image']); ?>"
                  alt="<?php echo htmlspecialchars($item['name']); ?>"
                  style="width:120px; height:90px; object-fit:cover; border-radius:16px; border:1px solid rgba(220,234,225,.95)"
                >

                <div style="flex:1 1 240px;">
                  <b><?php echo htmlspecialchars($item['name']); ?></b>
                  <div class="card__meta">Price: $<?php echo number_format($item['price'], 2); ?></div>
                  <div class="card__meta">Stock: <?php echo $item['stock']; ?></div>
                  <div class="card__meta">Subtotal: $<?php echo number_format($item['item_total'], 2); ?></div>
                </div>

                <div style="display:flex; flex-direction:column; gap:10px; min-width:180px;">
                  <div style="display:flex; align-items:center; gap:10px; justify-content:flex-end; flex-wrap:wrap;">
                    <a class="btn btn--ghost" href="cart.php?action=decrease&id=<?php echo $item['product_id']; ?>">-</a>

                    <span style="min-width:60px; text-align:center; font-weight:700;">
                      Qty: <?php echo $item['qty']; ?>
                    </span>

                    <a class="btn btn--ghost" href="cart.php?action=increase&id=<?php echo $item['product_id']; ?>">+</a>
                  </div>

                  <div style="text-align:right;">
                    <a
                      class="btn btn--ghost"
                      style="color: var(--ink); border-color: rgba(220,234,225,.95); background: rgba(255,255,255,.78)"
                      href="cart.php?action=remove&id=<?php echo $item['product_id']; ?>"
                    >
                      Remove
                    </a>
                  </div>
                </div>

              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="panel">
          <b style="color: var(--sage-700);">Order summary</b>

          <div style="margin-top:10px; display:grid; gap:6px; color: var(--muted); font-weight:650;">
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

          <a class="btn btn--primary" style="margin-top:12px; width:100%;" href="checkout.php">Checkout</a>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>

<footer class="footer">
  <div class="container">© 2026 AUREA – Floral & Plants</div>
</footer>

<script src="assets/js/main.js"></script>
</body>
</html>