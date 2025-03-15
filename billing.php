<?php
// Start session to manage user data
session_start();

// Include the database configuration file
$pdo = require 'dbConfig.php';

// Initialize variables for dynamic updates
$cardNumberPreview = "XXXX XXXX XXXX XXXX";
$expiryDatePreview = "MM/YY";
$cvvPreview = "CVV";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Retrieve and sanitize form data
        $cardNumber = trim($_POST['card-number']);
        $expiryDate = trim($_POST['expiry-date']);
        $cvv = trim($_POST['cvv']);

        // Validate inputs
        if (!preg_match('/^\d{4} \d{4} \d{4} \d{4}$/', $cardNumber)) {
            throw new Exception("Invalid card number format.");
        }
        if (!preg_match('/^\d{2}\/\d{2}$/', date('m/y', strtotime($expiryDate)))) {
            throw new Exception("Invalid expiry date format.");
        }
        if (!preg_match('/^\d{3}$/', $cvv)) {
            throw new Exception("Invalid CVV format.");
        }

        // Simulate payment processing (replace with actual payment gateway integration)
        $paymentStatus = simulatePaymentProcessing();

        // Insert payment details into the database
        if ($paymentStatus) {
            $stmt = $pdo->prepare("INSERT INTO payments (user_id, card_number, expiry_date, cvv, status) 
                                   VALUES (:user_id, :card_number, :expiry_date, :cvv, :status)");
            $stmt->execute([
                'user_id' => $_SESSION['user_id'] ?? 0, // Replace with actual logged-in user ID
                'card_number' => encryptData($cardNumber), // Encrypt sensitive data
                'expiry_date' => $expiryDate,
                'cvv' => encryptData($cvv), // Encrypt sensitive data
                'status' => 'success'
            ]);

            echo '<p style="color: green; text-align: center;">Payment processed successfully!</p>';
        } else {
            echo '<p style="color: red; text-align: center;">Payment failed. Please try again.</p>';
        }
    } catch (Exception $e) {
        echo '<p style="color: red; text-align: center;">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
}

// Helper function to simulate payment processing
function simulatePaymentProcessing() {
    // Simulate a successful payment (return true) or failure (return false)
    return true; // Replace with actual payment gateway logic
}

// Helper function to encrypt sensitive data
function encryptData($data) {
    return openssl_encrypt($data, 'AES-256-CBC', 'your_encryption_key', 0, '16_byte_iv_here');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Billing & Payments</title>
  <link rel="stylesheet" href="billing.css">
  <script>
    // Format card number with spaces
    function formatCardNumber(input) {
      const value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
      const formatted = value.match(/.{1,4}/g)?.join(' ') || '';
      input.value = formatted;
      document.getElementById('preview-card-number').textContent = formatted || 'XXXX XXXX XXXX XXXX';
    }

    // Validate numeric input
    function validateNumericInput(input) {
      input.value = input.value.replace(/\D/g, ''); // Allow only numbers
      document.getElementById('preview-cvv').textContent = input.value || 'CVV';
    }

    // Update expiry date preview
    function updateExpiryDate(input) {
      const value = input.value;
      document.getElementById('preview-expiry-date').textContent = value ? value.replace('-', '/') : 'MM/YY';
    }
  </script>
</head>
<body>
  <!-- Billing Section -->
  <section id="billing">
    <div class="container">
      <h2 class="section-title">Billing & Payments</h2>
      <div class="billing-container">
        <!-- Credit Card Preview -->
        <div class="card-preview">
          <div class="card-front">
            <div class="card-logo">💳 SecurePay</div>
            <div class="card-number" id="preview-card-number"><?php echo htmlspecialchars($cardNumberPreview); ?></div>
            <div class="card-details">
              <span class="expiry-date" id="preview-expiry-date"><?php echo htmlspecialchars($expiryDatePreview); ?></span>
              <span class="cvv" id="preview-cvv"><?php echo htmlspecialchars($cvvPreview); ?></span>
            </div>
          </div>
        </div>

        <!-- Payment Form -->
        <form id="payment-form" class="payment-form" method="POST" action="">
          <h3>Enter Payment Details</h3>
          <div class="input-group">
            <label for="card-number">Card Number:</label>
            <input type="text" id="card-number" name="card-number" placeholder="Enter Card Number (e.g., 1234 5678 9012 3456)" required maxlength="19"
              oninput="formatCardNumber(this)">
          </div>
          <div class="input-group">
            <label for="expiry-date">Expiry Date:</label>
            <input type="month" id="expiry-date" name="expiry-date" required onchange="updateExpiryDate(this)">
          </div>
          <div class="input-group">
            <label for="cvv">CVV:</label>
            <input type="text" id="cvv" name="cvv" placeholder="Enter CVV (e.g., 123)" required maxlength="3"
              oninput="validateNumericInput(this)">
          </div>
          <button type="submit" class="btn pay-now">Pay Now</button>
        </form>
      </div>
    </div>
  </section>
</body>
</html>