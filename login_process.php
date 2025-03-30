<?php
// session_start();
// include 'db.php';

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $username = $_POST["username"];
//     $password = $_POST["password"];

//     $sql = "SELECT * FROM employee WHERE username = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("s", $username);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     if ($result->num_rows > 0) {
//         $user = $result->fetch_assoc();

//         // Verify password (assuming passwords are hashed)
//         if (password_verify($password, $user['password'])) {
//             $_SESSION['loggedin'] = true;
//             $_SESSION['username'] = $user['username'];
//             header("Location: profile.php");
//             exit();
//         } else {
//             echo "Invalid password!";
//         }
//     } else {
//         echo "User not found!";
//     }

//     $stmt->close();
//     $conn->close();
// }

session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT * FROM employee WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Plain text password comparison (INSECURE - for testing only)
        if ($password === $user['password']) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user['username'];
            header("Location: profile.php");
            exit();
        } else {
            echo "Invalid password!";
            // Debug output
            echo "<br>Stored password: " . htmlspecialchars($user['password']);
            echo "<br>Entered password: " . htmlspecialchars($password);
        }
    } else {
        echo "User not found!";
    }

    $stmt->close();
    $conn->close();
}


?>