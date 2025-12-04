<?php
include "db.php";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect input data safely
    $name = $_POST['name'] ?? '';
    $usn = $_POST['usn'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';

    // Handle checkboxes safely
    $events = isset($_POST['events']) ? $_POST['events'] : [];
    if (empty($events)) {
        echo "Please select at least one event.";
        exit;
    }

    // Check for duplicate registrations
    $existing_events = [];
    $stmt_check = $conn->prepare("SELECT events FROM eventregistrations WHERE email = ?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    while ($row = $result_check->fetch_assoc()) {
        $existing = explode(", ", $row['events']);
        $existing_events = array_merge($existing_events, $existing);
    }
    $existing_events = array_unique($existing_events);
    $duplicates = array_intersect($events, $existing_events);
    if (!empty($duplicates)) {
        echo "You have already registered for: " . implode(", ", $duplicates) . ". Please select different events.";
        $stmt_check->close();
        exit;
    }
    $stmt_check->close();

    $event_list = implode(", ", $events);

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO eventregistrations (name, usn, email, phone, events) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $usn, $email, $phone, $event_list);

    if ($stmt->execute()) {
        // Success message with a button to go back
        echo "<h2>Registration Successful!</h2>";
        echo "<p>Thank you, $name.</p>";
        echo "<a href='nss1.html'>Go Home</a>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Please submit the form correctly.";
}
?>