<?php
session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<h2>Welcome <?php echo $_SESSION['user_name']; ?> 🎉</h2>



<h2>Admin Dashboard</h2>

<a href="add_book.php">Add New Book</a> |
<a href="manage_books.php">Manage Books</a> |
<a href="admin_orders.php">Manage Orders</a> |
<a href="logout.php">Logout</a>