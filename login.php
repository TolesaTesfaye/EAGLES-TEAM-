<?php
session_start();
  include "./dbConfig.php";
  include "./application/DB_FUNCTION/User.php";

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Subscription Management</title>
    <link rel="stylesheet" href="All-Css/login.css" />
  </head>
  <body>
    <script src="ALL JS/login.js"></script>
    <div class="login-container">
      <h2>Welcome Back</h2>
      <p class="subtitle">Login to manage your subscriptions</p>
     <?php if(isset($_GET['error']) ){ ?>
      <span><?php echo $_GET['error'] ;} ?> </span>
      <form action="./application/login.php" method="POST">
        <div class="input-group">
          <label for="email">Email Address</label>
          <input
            type="email"
            id="email"
            name="email"
            placeholder="Enter your email"
          />
        </div>
        <div class="input-group">
          <label for="password">Password</label>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="Enter your password"
          />
        </div>

        <button type="submit" class="login-btn">Log In</button>
      </form>

      <div class="extra-links">
        <a href="#" class="forgot-password">Forgot Password?</a>
      </div>

      <p class="signup-link">
        Don't have an account? <a href="./signup.php">Sign Up</a>
      </p>
    </div>
  </body>
</html>
