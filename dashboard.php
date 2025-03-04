<?php
session_start();

if (!isset($_SESSION['user_id'])) {
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

$user_id = $_SESSION['user_id'];

$sql = "SELECT p.full_name, p.phone_number, 
               s.shop_name, s.shop_address, s.shop_phone_number, s.shop_email, 
               b.business_registration_number, b.tin, b.vat_gst_number, b.annual_revenue, 
               k.account_name, k.account_number, k.bank_name, k.ifsc_code
        FROM personal_info p
        LEFT JOIN shop_info s ON p.personal_id = s.personal_id
        LEFT JOIN business_info b ON p.personal_id = b.personal_id
        LEFT JOIN bank_info k ON p.personal_id = k.personal_id
        WHERE p.personal_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($full_name, $phone_number, $shop_name, $shop_address, $shop_phone_number, $shop_email, 
                   $business_registration_number, $tin, $vat_gst_number, $annual_revenue, 
                   $account_name, $account_number, $bank_name, $ifsc_code);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($full_name); ?></h1>

        <div class="section">
            <h2>Personal Information</h2>
            <div class="info-group">
                <div class="info-item">
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
                </div>
                <div class="info-item">
                    <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($phone_number); ?></p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Shop Information</h2>
            <div class="info-group">
                <div class="info-item">
                    <p><strong>Shop Name:</strong> <?php echo htmlspecialchars($shop_name); ?></p>
                </div>
                <div class="info-item">
                    <p><strong>Shop Address:</strong> <?php echo htmlspecialchars($shop_address); ?></p>
                </div>
                <div class="info-item">
                    <p><strong>Shop Phone Number:</strong> <?php echo htmlspecialchars($shop_phone_number); ?></p>
                </div>
                <div class="info-item">
                    <p><strong>Shop Email:</strong> <?php echo htmlspecialchars($shop_email); ?></p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Business Information</h2>
            <div class="info-group">
                <div class="info-item">
                    <p><strong>Business Registration Number:</strong> <?php echo htmlspecialchars($business_registration_number); ?></p>
                </div>
                <div class="info-item">
                    <p><strong>TIN:</strong> <?php echo htmlspecialchars($tin); ?></p>
                </div>
                <div class="info-item">
                    <p><strong>VAT/GST Number:</strong> <?php echo htmlspecialchars($vat_gst_number); ?></p>
                </div>
                <div class="info-item">
                    <p><strong>Annual Revenue:</strong> <?php echo htmlspecialchars($annual_revenue); ?></p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Banking Information</h2>
            <div class="info-group">
                <div class="info-item">
                    <p><strong>Account Name:</strong> <?php echo htmlspecialchars($account_name); ?></p>
                </div>
                <div class="info-item">
                    <p><strong>Account Number:</strong> <?php echo htmlspecialchars($account_number); ?></p>
                </div>
                <div class="info-item">
                    <p><strong>Bank Name:</strong> <?php echo htmlspecialchars($bank_name); ?></p>
                </div>
                <div class="info-item">
                    <p><strong>IFSC Code:</strong> <?php echo htmlspecialchars($ifsc_code); ?></p>
                </div>
            </div>
        </div>

        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
