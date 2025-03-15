<?php
// Start session to manage user data
session_start();

// Include the database configuration file
$pdo = require 'dbConfig.php';

// Initialize variables for search and filter
$searchQuery = '';
$filterStatus = '';

// Handle search and filter functionality
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['search'])) {
        $searchQuery = trim($_GET['search']);
    }
    if (isset($_GET['filter'])) {
        $filterStatus = trim($_GET['filter']);
    }
}

// Prepare SQL query to fetch invoices
$sql = "SELECT * FROM invoices WHERE user_id = :user_id";
$params = ['user_id' => $_SESSION['user_id'] ?? 0]; // Replace with actual logged-in user ID

if (!empty($searchQuery)) {
    $sql .= " AND invoice_number LIKE :searchQuery";
    $params['searchQuery'] = '%' . $searchQuery . '%';
}
if (!empty($filterStatus)) {
    $sql .= " AND status = :filterStatus";
    $params['filterStatus'] = $filterStatus;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoices - Subscription Management System</title>
  <link rel="stylesheet" href="invoices.css">
</head>
<body>
  <!-- Invoices Section -->
  <section id="invoices">
    <div class="container">
      <h2>Invoices</h2>
      <div class="invoice-controls">
        <form method="GET" class="filter-form">
          <input type="text" name="search" placeholder="Search by Invoice ID..." value="<?php echo htmlspecialchars($searchQuery); ?>" />
          <select name="filter">
            <option value="">Filter by Status</option>
            <option value="paid" <?php echo ($filterStatus === 'paid') ? 'selected' : ''; ?>>Paid</option>
            <option value="pending" <?php echo ($filterStatus === 'pending') ? 'selected' : ''; ?>>Pending</option>
            <option value="failed" <?php echo ($filterStatus === 'failed') ? 'selected' : ''; ?>>Failed</option>
          </select>
          <button type="submit" class="btn filter-btn">Apply Filter</button>
        </form>
      </div>
      <table class="invoice-table">
        <thead>
          <tr>
            <th>Invoice ID</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($invoices)): ?>
            <?php foreach ($invoices as $invoice): ?>
              <tr>
                <td><?php echo htmlspecialchars($invoice['invoice_number']); ?></td>
                <td><?php echo htmlspecialchars($invoice['issue_date']); ?></td>
                <td>$<?php echo number_format($invoice['total_amount'], 2); ?></td>
                <td>
                  <span class="status <?php echo htmlspecialchars($invoice['status']); ?>">
                    <?php echo ucfirst(htmlspecialchars($invoice['status'])); ?>
                  </span>
                </td>
                <td>
                  <?php if ($invoice['status'] === 'paid'): ?>
                    <a href="download_invoice.php?id=<?php echo $invoice['invoice_id']; ?>" class="btn download-btn">Download</a>
                  <?php else: ?>
                    <button class="btn download-btn disabled" disabled>Download</button>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5">No invoices found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>
</body>
</html>