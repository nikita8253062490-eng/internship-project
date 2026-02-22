<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$sql = "SELECT orders.*, users.name 
        FROM orders 
        JOIN users ON orders.user_id = users.id
        ORDER BY orders.created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - All Orders</title>
</head>
<body>

<h2>All Orders (Admin)</h2>
<a href="dashboard.php">Back to Dashboard</a>
<br><br>

<table border="1" cellpadding="10">
<tr>
    <th>Order ID</th>
    <th>User Name</th>
    <th>Total Amount</th>
    <th>Date</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td>₹<?php echo $row['total_amount']; ?></td>
    <td><?php echo $row['created_at']; ?></td>
</tr>
<?php } ?>

</table>

</body>
</html>