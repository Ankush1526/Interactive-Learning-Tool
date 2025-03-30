<?php
include 'db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $gender = $_POST["gender"];
    $country = $_POST["country"];

    // Insert user into the database
    $sql = "INSERT INTO employee (username, password, gender, country) VALUES ('$username', '$password', '$gender', '$country')";

    if ($conn->query($sql) === TRUE) {
        echo "Signup successful! <a href='login.php'>Login here</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>