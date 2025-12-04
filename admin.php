<?php
include "db.php";
$result = $conn->query("SELECT * FROM participants");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Panel</title>
<style>
table{
  border-collapse:collapse;width:100%;
  font-family:Arial;margin-top:20px;
}
th,td{
  border:1px solid #444;padding:8px;text-align:left;
}
</style>
</head>
<body>

<h2>Participants List</h2>

<table>
<tr>
  <th>ID</th><th>Name</th><th>USN</th><th>Email</th>
  <th>Phone</th><th>Events</th><th>Date</th>
</tr>

<?php while($row = $result->fetch_assoc()){ ?>
<tr>
  <td><?= $row['id'] ?></td>
  <td><?= $row['name'] ?></td>
  <td><?= $row['usn'] ?></td>
  <td><?= $row['email'] ?></td>
  <td><?= $row['phone'] ?></td>
  <td><?= $row['events'] ?></td>
  <td><?= $row['created_at'] ?></td>
</tr>
<?php } ?>

</table>

</body>
</html>
