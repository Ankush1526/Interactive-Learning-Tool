<?php
session_start();


if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$host = 'localhost';
$dbname = 'ankush_1';
$user = 'root';
$pass = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all user details
    $stmt = $conn->prepare("SELECT username, gender, country FROM employee WHERE username = :username");
    $stmt->bindParam(':username', $_SESSION['username']);
    $stmt->execute();

    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user_data) {
        // User not found in database (but session exists)
        $user_data = [
            'username' => $_SESSION['username'],
            'gender' => 'Not specified',
            'country' => 'Not specified'
        ];
    }
} catch (PDOException $e) {
    // Fallback if database connection fails
    $user_data = [
        'username' => $_SESSION['username'],
        'gender' => 'Error loading',
        'country' => 'Error loading'
    ];
    error_log("Database error: " . $e->getMessage());
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - <?php echo htmlspecialchars($user_data['username']); ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 30px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }

        .profile-pic {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 20px;
            border: 3px solid #4CAF50;
            background-color: #ddd;
        }

        .profile-info h2 {
            margin: 0;
            font-size: 24px;
        }

        .profile-info p {
            margin: 5px 0 0;
            color: #666;
        }

        .profile-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .detail-group {
            margin-bottom: 15px;
        }

        .detail-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
            display: block;
        }

        .detail-value {
            padding: 8px 12px;
            background: #f9f9f9;
            border-radius: 5px;
            border-left: 3px solid #4CAF50;
        }

        .action-buttons {
            margin-top: 30px;
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-edit {
            background-color: #4CAF50;
            color: white;
        }

        .btn-password {
            background-color: #2196F3;
            color: white;
        }

        .btn-logout {
            background-color: #f44336;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="profile-header">
            <img src="default-profile.jpg" alt="Profile Picture" class="profile-pic">
            <div class="profile-info">
                <h2><?php echo htmlspecialchars($user_data['username']); ?></h2>
                <p>@<?php echo htmlspecialchars($user_data['username']); ?></p>
            </div>
        </div>

        <div class="profile-details">
            <div class="detail-group">
                <span class="detail-label">Username</span>
                <div class="detail-value"><?php echo htmlspecialchars($user_data['username']); ?></div>
            </div>
            <div class="detail-group">
                <span class="detail-label">Gender</span>
                <div class="detail-value"><?php echo htmlspecialchars($user_data['gender']); ?></div>
            </div>
            <div class="detail-group">
                <span class="detail-label">Country</span>
                <div class="detail-value"><?php echo htmlspecialchars($user_data['country']); ?></div>
            </div>
        </div>

        <!-- <div class="action-buttons">
            <a href="edit_profile.php" class="btn btn-edit">Edit Profile</a>
            <a href="edit_password.php" class="btn btn-password">Edit Password</a>
            <a href="logout.php" class="btn btn-logout">Logout</a>
        </div> -->
    </div>
</body>

</html>