<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "Pass123";
$dbname = "my_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT p.full_name, p.email, p.phone_number, 
               s.shop_name, s.shop_address, s.shop_phone_number, s.shop_email, 
               b.business_registration_number, b.tin, b.vat_gst_number, b.annual_revenue, 
               k.account_name, k.account_number, k.bank_name, k.ifsc_code
        FROM personal_info p
        LEFT JOIN shop_info s ON p.personal_id = s.personal_id
        LEFT JOIN business_info b ON p.personal_id = b.personal_id
        LEFT JOIN bank_info k ON p.personal_id = k.personal_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $users = [];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>

        <?php foreach ($users as $user): ?>
            <div class="section">
                <h2><?php echo htmlspecialchars($user['full_name']); ?>'s Information</h2>
                <div class="info-group">
                    <div class="info-item">
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                    <div class="info-item">
                        <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($user['phone_number']); ?></p>
                    </div>
                    <div class="info-item">
                        <p><strong>Shop Name:</strong> <?php echo htmlspecialchars($user['shop_name']); ?></p>
                    </div>
                    <div class="info-item">
                        <p><strong>Shop Address:</strong> <?php echo htmlspecialchars($user['shop_address']); ?></p>
                    </div>
                    <div class="info-item">
                        <p><strong>Shop Phone Number:</strong> <?php echo htmlspecialchars($user['shop_phone_number']); ?></p>
                    </div>
                    <div class="info-item">
                        <p><strong>Shop Email:</strong> <?php echo htmlspecialchars($user['shop_email']); ?></p>
                    </div>
                    <div class="info-item">
                        <p><strong>Business Registration Number:</strong> <?php echo htmlspecialchars($user['business_registration_number']); ?></p>
                    </div>
                    <div class="info-item">
                        <p><strong>TIN:</strong> <?php echo htmlspecialchars($user['tin']); ?></p>
                    </div>
                    <div class="info-item">
                        <p><strong>VAT/GST Number:</strong> <?php echo htmlspecialchars($user['vat_gst_number']); ?></p>
                    </div>
                    <div class="info-item">
                        <p><strong>Annual Revenue:</strong> <?php echo htmlspecialchars($user['annual_revenue']); ?></p>
                    </div>
                    <div class="info-item">
                        <p><strong>Account Name:</strong> <?php echo htmlspecialchars($user['account_name']); ?></p>
                    </div>
                    <div class="info-item">
                        <p><strong>Account Number:</strong> <?php echo htmlspecialchars($user['account_number']); ?></p>
                    </div>
                    <div class="info-item">
                        <p><strong>Bank Name:</strong> <?php echo htmlspecialchars($user['bank_name']); ?></p>
                    </div>
                    <div class="info-item">
                        <p><strong>IFSC Code:</strong> <?php echo htmlspecialchars($user['ifsc_code']); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
