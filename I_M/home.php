<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home - New Dawn Thrift</title>
  <link rel="stylesheet" href="./home.css?v=3">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

  <!-- Working Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>

  <!-- HEADER -->
  <header>
    <div class="logo-left">
      <img src="logo.png" alt="Logo">
    </div>

    <nav class="nav-center">
      <a href="#" class="active">HOME</a>
      <a href="product1.php">PRODUCT</a>
      <a href="aboutus.html">ABOUT US</a>
      <a href="contactus.php">CONTACT US</a>
    </nav>

    <div class="right-icons">
      <a href="cart.php" class="cart-btn" aria-label="Cart"><i class="fas fa-shopping-cart"></i></a>
      <a href="login.php" class="logout-btn">Logout</a>
    </div>
  </header>

  <!-- MAIN SECTION -->
  <main>
    <div class="hero">
      <h2>Where vintage charm meets modern style,<br>giving fashion a second chance to shine.</h2>
      <p>Unveiling a thrift destination where every piece has a past, yet speaks to the present. Here, fashion finds new life, sustainability becomes style, and your wardrobe grows with unique treasures that tell a story worth wearing.</p>

      <button class="collection-btn" onclick="window.location.href='product4.php'">
        New Collection <span class="arrow-circle"><i class="fas fa-arrow-right"></i></span>
      </button>

      <div class="gallery">
        <a href="product1.php"><img src="LEFT.jpg" alt="Left"></a>
        <a href="product2.php"><img src="MIDDLE.jpg" alt="Middle"></a>
        <a href="product3.php"><img src="RIGHT.jpg" alt="Right"></a>
      </div>
    </div>
  </main>

  <!-- FOOTER -->
  <footer>
    <hr>
    <div class="footer-container">
      <div class="footer-left">
        <p>About Us</p>
        <p>Terms & Condition</p>
      </div>
      <div class="footer-center">
        <p class="brand">NEW DAWN THRIFT</p>
        <p>Follow Us</p>
        <div class="socials">
          <a href="https://www.facebook.com" target="_blank"><img src="facebook.png" alt="Facebook"></a>
          <a href="https://www.instagram.com" target="_blank"><img src="instagram.png" alt="Instagram"></a>
          <a href="https://www.tiktok.com" target="_blank"><img src="tiktok.png" alt="Tiktok"></a>
        </div>
      </div>
      <div class="footer-right">
        <p>Privacy Policy</p>
        <p>Contact Us</p>
        <p>+63-2-8123-4567</p>
        <p>newdawnthrift@gmail.com</p>
      </div>
    </div>
  </footer>

</body>
</html>
