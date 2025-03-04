<?php
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

// Collect data from form
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
$phone_number = $_POST['phone_number'];
$shop_name = $_POST['shop_name'];
$shop_address = $_POST['shop_address'];
$shop_phone_number = $_POST['shop_phone_number'];
$shop_email = $_POST['shop_email'];
$business_registration_number = $_POST['business_registration_number'];
$tin = $_POST['tin'];
$vat_gst_number = $_POST['vat_gst_number'];
$annual_revenue = $_POST['annual_revenue'];
$account_name = $_POST['account_name'];
$account_number = $_POST['account_number'];
$bank_name = $_POST['bank_name'];
$ifsc_code = $_POST['ifsc_code'];

// Start transaction
$conn->begin_transaction();

try {
    // Insert personal information
    $stmt = $conn->prepare("INSERT INTO personal_info (full_name, email, password, phone_number) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception($conn->error);
    }
    $stmt->bind_param("ssss", $full_name, $email, $password, $phone_number);
    $stmt->execute();
    $personal_id = $stmt->insert_id; // Get the last inserted ID
    $stmt->close();

    // Insert shop information
    $stmt = $conn->prepare("INSERT INTO shop_info (shop_name, shop_address, shop_phone_number, shop_email, personal_id) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception($conn->error);
    }
    $stmt->bind_param("ssssi", $shop_name, $shop_address, $shop_phone_number, $shop_email, $personal_id);
    $stmt->execute();
    $stmt->close();

    // Insert business information
    $stmt = $conn->prepare("INSERT INTO business_info (business_registration_number, tin, vat_gst_number, annual_revenue, personal_id) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception($conn->error);
    }
    $stmt->bind_param("ssssi", $business_registration_number, $tin, $vat_gst_number, $annual_revenue, $personal_id);
    $stmt->execute();
    $stmt->close();

    // Insert banking information
    $stmt = $conn->prepare("INSERT INTO bank_info (account_name, account_number, bank_name, ifsc_code, personal_id) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception($conn->error);
    }
    $stmt->bind_param("ssssi", $account_name, $account_number, $bank_name, $ifsc_code, $personal_id);
    $stmt->execute();
    $stmt->close();

    // Commit transaction
    $conn->commit();
    // Display success message with a button to go to the login page
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registration Successful</title>
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(to right, #6a11cb, #2575fc);
                margin: 0;
            }
            .container {
                text-align: center;
                background: #ffffff;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                max-width: 400px;
                width: 100%;
                margin: 20px;
            }
            .container h2 {
                margin-bottom: 20px;
                color: #333;
            }
            .container p {
                margin-bottom: 20px;
                color: #555;
            }
            .container a {
                display: inline-block;
                padding: 12px 24px;
                background-color: #007bff;
                color: #ffffff;
                text-decoration: none;
                border-radius: 5px;
                font-weight: bold;
                transition: background-color 0.3s, transform 0.3s;
            }
            .container a:hover {
                background-color: #0056b3;
                transform: scale(1.05);
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Registration Successful</h2>
            <p>Your account has been created successfully. You can now log in with your new account.</p>
            <a href="login.html">Go to Login</a>
        </div>
    </body>
    </html>
    ';
} catch (Exception $e) {
    // Rollback transaction if something goes wrong
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

// Close connection
$conn->close();
?>
