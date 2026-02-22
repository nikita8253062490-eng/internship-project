<?php
session_start();
include '../config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if($_SESSION['user_role'] != 'admin') {
    echo "Access Denied!";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>

<h2>Admin Dashboard</h2>

<a href="add_book.php">Add New Book</a> |
<a href="manage_books.php">Manage Books</a> |
<a href="../logout.php">Logout</a>

</body>
</html>