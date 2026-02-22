<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT books.*, cart.quantity 
        FROM cart 
        JOIN books ON cart.book_id = books.id 
        WHERE cart.user_id = $user_id";

$result = mysqli_query($conn, $sql);

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Cart</title>
</head>
<body>

<h2>My Cart</h2>

<a href="index.php">Continue Shopping</a>
<br><br>

<table border="1" cellpadding="10">
<tr>
    <th>Book</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Total</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { 
    $subtotal = $row['price'] * $row['quantity'];
    $total += $subtotal;
?>
<tr>
    <td><?php echo $row['title']; ?></td>
    <td>₹<?php echo $row['price']; ?></td>
    <td><?php echo $row['quantity']; ?></td>
    <td>₹<?php echo $subtotal; ?></td>
</tr>
<?php } ?>

<tr>
    <td colspan="3"><strong>Grand Total</strong></td>
    <td><strong>₹<?php echo $total; ?></strong></td>
</tr>

</table>

<br>
<br><br>
<a href="place_order.php">
    <button>Place Order</button>
</a>

</body>
</html>