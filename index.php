<?php
include 'db.php';

$sql = "SELECT product_id, name, price, quantity, description, image FROM products";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>AUREA – Floral & Plants</title>
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
      <a href="cart.html">Cart</a>
      <a href="contact.html">Contact</a>
      <a class="cta" href="cart.html">Checkout</a>
    </nav>
  </div>
</header>

<section class="hero">
  <div class="container">
    <div class="hero__wrap">
      <div class="hero__content">
        <span class="badge">Premium Plant Store • Calm Aesthetic</span>
        <h1>Discover beautiful indoor Dhai for every corner of your home</h1>
        <p>A modern plant shop experience — clean layout, soft greens, and premium cards. Prototype UI for CIS311.</p>
        <div class="hero__actions">
          <a class="btn btn--primary" href="#plants">Shop now</a>
          <a class="btn btn--ghost" href="contact.html">Find us</a>
        </div>
      </div>

      <div class="hero__stats">
        <div class="stat"><b>1,900+</b><span>Happy plant lovers</span></div>
        <div class="stat"><b>8,000+</b><span>Plants delivered</span></div>
        <div class="stat"><b>520+</b><span>Local greenhouses</span></div>
        <div class="stat"><b>4.9★</b><span>Customer rating</span></div>
      </div>
    </div>
  </div>
</section>

<section class="section" id="plants">
  <div class="container">
    <div class="section__head">
      <div>
        <h2>Our favorite plants</h2>
        <p>Clean product cards with calm spacing — inspired by your reference style, built as an original layout for AUREA.</p>
      </div>
      <a class="btn btn--primary" href="product.php">View product</a>
    </div>

    <div class="grid">
      <?php if ($result->num_rows > 0) { ?>
        <?php while ($row = $result->fetch_assoc()) { ?>
          <a class="card" href="product.php?id=<?php echo (int)$row['product_id']; ?>">
            <img class="card__img" src="assets/images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
            <div class="card__body">
              <div class="card__title"><?php echo htmlspecialchars($row['name']); ?></div>
              <div class="card__meta"><?php echo htmlspecialchars($row['description']); ?></div>
              <div class="card__row">
                <div class="price">$<?php echo number_format($row['price'], 2); ?></div>
                <div class="pill">+</div>
              </div>
            </div>
          </a>
        <?php } ?>
      <?php } else { ?>
        <p>No products found.</p>
      <?php } ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="container split">
    <div class="panel">
      <div class="section__head" style="margin-top:0">
        <div>
          <h2>Why shop with AUREA?</h2>
          <p>Course-ready sections: delivery, support, secure payments, and easy returns.</p>
        </div>
      </div>

      <div style="display:grid; gap:12px">
        <div class="feature">
          <div class="icon">🚚</div>
          <div>
            <h3>Fast delivery</h3>
            <p>Plants arrive fresh with careful packaging and simple tracking.</p>
          </div>
        </div>

        <div class="feature">
          <div class="icon">↩</div>
          <div>
            <h3>Hassle-free returns</h3>
            <p>Easy policy for damaged plants — keep your experience stress-free.</p>
          </div>
        </div>

        <div class="feature">
          <div class="icon">🔒</div>
          <div>
            <h3>Secure payments</h3>
            <p>Checkout flow designed to be clear, safe, and minimal.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="panel" style="background: linear-gradient(180deg, rgba(255,255,255,.92), rgba(238,246,240,.85));">
      <h2 style="color: var(--sage-700);">Spring into green</h2>
      <p style="color: var(--muted); margin-top:6px;">Up to <b>25% off</b> selected indoor plants.</p>
      <div style="margin-top:12px; display:flex; gap:10px; flex-wrap:wrap;">
        <a class="btn btn--primary" href="product.php">Shop the sale</a>
        <a class="btn btn--ghost" style="color: var(--ink); border-color: rgba(220,234,225,.9); background: rgba(255,255,255,.75)" href="#plants">Browse</a>
      </div>
      <img style="margin-top:12px; border-radius: 18px; border: 1px solid rgba(220,234,225,.9)" src="assets/images/room.jpg" alt="Plants in room">
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="dark-strip">
      <h2>Get the green in your inbox</h2>
      <p>Premium dark section like your reference — but customized for our UI.</p>

      <div class="newsletter">
        <input class="input" placeholder="Enter your email" />
        <a class="btn btn--primary" href="#">Subscribe</a>
      </div>
      <div class="small">Prototype only. No emails are collected.</div>
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
        <a href="product.php">Product</a>
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

<script src="assets/js/main.js"></script>
</body>
</html>