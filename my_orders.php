<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$orders = mysqli_query($conn,
    "SELECT * FROM orders WHERE user_id=$user_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
</head>
<body>

<h2>My Orders</h2>
<a href="index.php">Back to Store</a>
<br><br>

<?php while($order = mysqli_fetch_assoc($orders)) { ?>

    <h3>Order #<?php echo $order['id']; ?></h3>
    <p>Date: <?php echo $order['created_at']; ?></p>
    <p>Total: ₹<?php echo $order['total_amount']; ?></p>

    <table border="1" cellpadding="8">
        <tr>
            <th>Book</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>

        <?php
        $items = mysqli_query($conn,
            "SELECT books.title, order_items.quantity, order_items.price
             FROM order_items
             JOIN books ON order_items.book_id = books.id
             WHERE order_items.order_id = ".$order['id']);

        while($item = mysqli_fetch_assoc($items)) {
        ?>

        <tr>
            <td><?php echo $item['title']; ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>₹<?php echo $item['price']; ?></td>
        </tr>

        <?php } ?>

    </table>

    <hr>

<?php } ?>

</body>
</html>