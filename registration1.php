<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login11.html');
    exit();
}
include "db.php";
$email = $_SESSION['email'];
$registered_events = [];
$stmt = $conn->prepare("SELECT events FROM eventregistrations WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $events = explode(", ", $row['events']);
    $registered_events = array_merge($registered_events, $events);
}
$registered_events = array_unique($registered_events);
$stmt->close();

$all_events = ["Pick & Speak", "Painting", "Debate", "Essay Writing", "Poster Making", "Cleanliness Drive", "Yoga Competition"];
$available_events = array_diff($all_events, $registered_events);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Registration</title>
<style>
body{
  margin:0;font-family:Arial;
  background:linear-gradient(135deg,#f72585,#7209b7);
  display:flex;align-items:center;justify-content:center;
  height:100vh;color:white;
}
.card{
  background:rgba(255,255,255,0.1);
  padding:30px;border-radius:20px;width:400px;
}
input,button{
  width:100%;padding:10px;margin:5px;border-radius:10px;
}
button{background:#4cc9f0;border:none;color:black;font-weight:700;}
label{display:block;margin:3px 0;}
</style>
</head>
<body>

<div class="card">
  <h2>Event Registration</h2>
  <form method="POST" action="save.php">
    
    <input type="text" name="name" required placeholder="Full Name">
    <input type="text" name="usn" required placeholder="USN / Student ID">
    <input type="email" name="email" required placeholder="Email">
    <input type="text" name="phone" required placeholder="Phone">

    <h4>Select Events</h4>

    <?php if (empty($available_events)): ?>
        <p>You have already registered for all available events.</p>
    <?php else: ?>
        <?php foreach ($available_events as $event): ?>
            <label><input type="checkbox" name="events[]" value="<?php echo htmlspecialchars($event); ?>"> <?php echo htmlspecialchars($event); ?></label>
        <?php endforeach; ?>
    <?php endif; ?>

    <button type="submit">Submit Registration</button>
  </form>
</div>

</body>
</html>
