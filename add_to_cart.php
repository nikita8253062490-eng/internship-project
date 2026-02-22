<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$book_id = $_GET['id'];

// Check if book already in cart
$stmt = mysqli_prepare($conn,
    "SELECT * FROM cart WHERE user_id=? AND book_id=?");
mysqli_stmt_bind_param($stmt, "ii", $user_id, $book_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) > 0) {

    // If already exists → increase quantity
    mysqli_query($conn,
        "UPDATE cart SET quantity = quantity + 1 
         WHERE user_id=$user_id AND book_id=$book_id");

} else {

    // Insert new cart item
    $stmt = mysqli_prepare($conn,
        "INSERT INTO cart (user_id, book_id) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $book_id);
    mysqli_stmt_execute($stmt);
}

header("Location: index.php");
exit();
?>