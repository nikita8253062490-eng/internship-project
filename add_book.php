<?php
session_start();
include '../config.php';

if($_SESSION['user_role'] != 'admin') {
    echo "Access Denied!";
    exit();
}

if(isset($_POST['add_book'])) {

    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Image Upload
    $image = $_FILES['image']['name'];
    $temp = $_FILES['image']['tmp_name'];

    move_uploaded_file($temp, "../uploads/" . $image);

    $stmt = mysqli_prepare($conn,
        "INSERT INTO books (title, author, price, description, image) 
         VALUES (?, ?, ?, ?, ?)");

    mysqli_stmt_bind_param($stmt, "ssdss",
        $title, $author, $price, $description, $image);

    mysqli_stmt_execute($stmt);

    echo "Book Added Successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
</head>
<body>

<h2>Add New Book</h2>

<form method="POST" enctype="multipart/form-data">

    <label>Title:</label><br>
    <input type="text" name="title" required><br><br>

    <label>Author:</label><br>
    <input type="text" name="author" required><br><br>

    <label>Price:</label><br>
    <input type="number" step="0.01" name="price" required><br><br>

    <label>Description:</label><br>
    <textarea name="description"></textarea><br><br>

    <label>Book Image:</label><br>
    <input type="file" name="image" required><br><br>

    <button type="submit" name="add_book">Add Book</button>
</form>

<br>
<a href="admin_dashboard.php">Back to Dashboard</a>

</body>
</html>