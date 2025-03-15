<?php
// Start session to manage user data
session_start();

// Include the database configuration file
$pdo = require 'dbConfig.php';

// Fetch subscription plans from the database
try {
    $stmt = $pdo->query("SELECT * FROM subscription_plans");
    $plans = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '<p style="color: red; text-align: center;">Error fetching subscription plans: ' . htmlspecialchars($e->getMessage()) . '</p>';
    $plans = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Subscription Management System</title>
  <link rel="stylesheet" href="sub_plan.css">
</head>
<body>
  <!-- Subscription Plans Section -->
  <section id="plans" class="plans">
    <div class="container">
      <h2>Subscription Plans</h2>
      <div class="plan-grid">
        <?php if (!empty($plans)): ?>
          <?php foreach ($plans as $plan): ?>
            <div class="plan <?php echo htmlspecialchars($plan['plan_name']); ?>">
              <h3><?php echo htmlspecialchars($plan['plan_name']); ?></h3>
              <p class="price">$<?php echo number_format($plan['price'], 2); ?><span>/month</span></p>
              <ul>
                <?php
                // Split features into an array and display them as list items
                $features = explode(',', $plan['description']);
                foreach ($features as $feature) {
                    echo '<li><i class="fas fa-check"></i> ' . htmlspecialchars(trim($feature)) . '</li>';
                }
                ?>
              </ul>
              <form method="POST" action="subscribe.php">
                <input type="hidden" name="plan_id" value="<?php echo htmlspecialchars($plan['plan_id']); ?>">
                <button type="submit" class="btn">Subscribe</button>
              </form>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="text-align: center;">No subscription plans available.</p>
        <?php endif; ?>
      </div>
    </div>
  </section>
</body>
</html>