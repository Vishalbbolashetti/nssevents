<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Use prepared statement
    $stmt = $conn->prepare("INSERT INTO applicants(email, password) VALUES (?, ?)");
    if (!$stmt) {
        echo "Prepare failed: " . $conn->error;
        exit();
    }
    $stmt->bind_param("ss", $email, $hashed_password);

    if ($stmt->execute()) {
        // Start session and store email
        session_start();
        $_SESSION['email'] = $email;
        // Redirect to rulebook1.php after successful registration
        header('Location: rulebook1.php');
        exit();
    } else {
        echo "Execute failed: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Please submit the form correctly.";
}
?>