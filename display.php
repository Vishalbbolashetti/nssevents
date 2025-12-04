<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    die("Access denied! <a href='login.html'>Login</a>");
}

$sql = "SELECT id, name, email, phone, username, events FROM users";
$result = $conn->query($sql);

echo "<h2>Registered Users</h2>";
echo "<table border='1' cellpadding='10'>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Username</th>
            <th>Events</th>
        </tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['email']}</td>
            <td>{$row['phone']}</td>
            <td>{$row['username']}</td>
            <td>{$row['events']}</td>
          </tr>";
}

echo "</table>";

$conn->close();
?>
