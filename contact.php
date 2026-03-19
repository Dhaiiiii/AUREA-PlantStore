<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>AUREA – Contact</title>
  <link rel="stylesheet" href="assets/css/styles.css" />
  <script src="validation.js"></script>

  <style>
    .form-input {
      width: 100%;
      padding: 12px;
      border: 1px solid #dceae1;
      border-radius: 8px;
      background: #fff;
      font-size: 15px;
      color: var(--sage-800);
      transition: 0.2s ease;
    }

    .form-input:focus {
      border-color: var(--sage-600);
      box-shadow: 0 0 0 3px rgba(142, 180, 160, 0.25);
      outline: none;
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
      <a href="cart.php">Cart</a>
      <a href="contact.php">Contact</a>
      <a class="cta" href="cart.php">Checkout</a>
    </nav>
  </div>
</header>

<section class="section">
  <div class="container split">

    <!-- LEFT PANEL -->
    <div class="panel">
      <h2 style="color: var(--sage-700);">Contact</h2>
      <p style="color: var(--muted); margin-top:6px;">Al Khobar, Saudi Arabia</p>

      <!-- CONTACT FORM -->
      <form style="margin-top:20px; display:grid; gap:12px;" onsubmit="return validateContact();">

        <div>
          <label>Name</label>
          <input type="text" id="contact_name" class="form-input" required>
        </div>

        <div>
          <label>Email</label>
          <input type="email" id="contact_email" class="form-input" required>
        </div>

        <div>
          <label>Message</label>
          <textarea id="contact_message" class="form-input" rows="4" required></textarea>
        </div>

        <button class="btn btn--primary" type="submit">Send Message</button>
      </form>

      <!-- INFO BOXES -->
      <div style="margin-top:20px; display:grid; gap:10px;">
        <div class="feature">
          <div class="icon">📞</div>
          <div><h3>Support</h3><p>+966 5X XXX XXXX</p></div>
        </div>
        <div class="feature">
          <div class="icon">🕒</div>
          <div><h3>Hours</h3><p>Sun–Thu • 10:00–18:00</p></div>
        </div>
      </div>
    </div>

    <!-- RIGHT PANEL (MAP) -->
    <div class="panel" style="padding:0; overflow:hidden;">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3584.292982520824!2d50.207!3d26.279!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e49e63e9e0b0001%3A0x0000000000000000!2sAl%20Khobar%2C%20Saudi%20Arabia!5e0!3m2!1sen!2ssa!4v1700000000000"
        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
      </iframe>
    </div>

  </div>
</section>

<footer class="footer">
  <div class="container">© 2026 AUREA – Floral & Plants</div>
</footer>

</body>
</html>