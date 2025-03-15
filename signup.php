<?php

// Start session (optional, depending on your use case)
// Include the database configuration file
session_start();
// print_r($users);
// Include the database configuration file
$pdo = require 'dbConfig.php';

// Initialize variables for feedback messages
$successMessage = '';
$errorMessage = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Retrieve and sanitize form data
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        // Validate inputs
        if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
            throw new Exception("All fields are required.");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }
        if ($password !== $confirmPassword) {
            throw new Exception("Passwords do not match.");
        }
        if (strlen($password) < 6) {
            throw new Exception("Password must be at least 6 characters long.");
        }

        // Check if the email is already registered
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            throw new Exception("Email is already registered.");
        }

        // Hash the password securely
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)");
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password_hash' => $hashedPassword
        ]);

        // Provide success feedback
        $successMessage = "Account created successfully! You can now <a href='login.php'>log in</a>.";
    } catch (Exception $e) {
        // Provide error feedback
        $errorMessage = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up</title>
  <link rel="stylesheet" href="All-Css/signup.css" />
</head>
<body>
  <div class="signup-container">
    <h2>Create Your Account</h2>

    <?php if ($successMessage): ?>
      <p style="color: green; text-align: center;"><?php echo $successMessage; ?></p>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
      <p style="color: red; text-align: center;"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <form action="./application/sign_up.php" method="POST">
      <div class="input-group">
        <label for="username">Username</label>
        <input
          type="text"
          id="username"
          name="username"
          required
          
          placeholder="Enter your username"
          value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
        />
      </div>
      <div class="input-group">
        <label for="email">Email</label>
        <input
          type="email"
          id="email"
          name="email"
          required
          
          placeholder="Enter your email"
          value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
        />
      </div>
      <div class="input-group">
        <label for="password">Password</label>
        <input
          type="password"
          id="password"
          name="password"
          required
       
          placeholder="Enter your password"
        />
      </div>
      <div class="input-group">
        <label for="confirm-password">Confirm Password</label>
        <input
          type="password"
          id="confirm-password"
          name="confirm_password"
          required
          placeholder="Confirm your password"
        />
      </div>
      <button type="submit" name="submit" class="signup-btn">Sign Up</button>
      <p class="login-link">
        Already have an account? <a href="./login.php">Log in</a>
      </p>
    </form>
  </div>
</body>
</html>