<?php
session_start();
include '../config.php';

if($_SESSION['user_role'] != 'admin') {
    echo "Access Denied!";
    exit();
}

if(isset($_GET['id'])) {

    $id = $_GET['id'];

    // Get image name first
    $result = mysqli_query($conn, "SELECT image FROM books WHERE id=$id");
    $book = mysqli_fetch_assoc($result);

    // Delete image file
    if($book && file_exists("../uploads/" . $book['image'])) {
        unlink("../uploads/" . $book['image']);
    }

    // Delete book from database
    mysqli_query($conn, "DELETE FROM books WHERE id=$id");

    header("Location: manage_books.php");
    exit();
}
?>