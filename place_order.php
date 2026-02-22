<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get cart items
$sql = "SELECT books.*, cart.quantity 
        FROM cart 
        JOIN books ON cart.book_id = books.id 
        WHERE cart.user_id = $user_id";

$result = mysqli_query($conn, $sql);

$total = 0;
$items = [];

while($row = mysqli_fetch_assoc($result)) {
    $subtotal = $row['price'] * $row['quantity'];
    $total += $subtotal;
    $items[] = $row;
}

if(empty($items)) {
    echo "Cart is empty!";
    exit();
}

// Insert into orders table
$stmt = mysqli_prepare($conn,
    "INSERT INTO orders (user_id, total_amount) VALUES (?, ?)");
mysqli_stmt_bind_param($stmt, "id", $user_id, $total);
mysqli_stmt_execute($stmt);

$order_id = mysqli_insert_id($conn);

// Insert into order_items
foreach($items as $item) {

    $stmt = mysqli_prepare($conn,
        "INSERT INTO order_items 
         (order_id, book_id, quantity, price) 
         VALUES (?, ?, ?, ?)");

    mysqli_stmt_bind_param($stmt, "iiid",
        $order_id,
        $item['id'],
        $item['quantity'],
        $item['price']
    );

    mysqli_stmt_execute($stmt);
}

// Clear cart
mysqli_query($conn, "DELETE FROM cart WHERE user_id=$user_id");

echo "Order Placed Successfully!";
?>
<br><br>
<a href="index.php">Back to Store</a>