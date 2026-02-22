<?php
session_start();
include '../config.php';

if($_SESSION['user_role'] != 'admin') {
    echo "Access Denied!";
    exit();
}

$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM books WHERE id=$id");
$book = mysqli_fetch_assoc($result);

if(isset($_POST['update_book'])) {

    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    if($_FILES['image']['name']) {

        $image = $_FILES['image']['name'];
        $temp = $_FILES['image']['tmp_name'];

        move_uploaded_file($temp, "../uploads/" . $image);

        mysqli_query($conn, 
            "UPDATE books SET title='$title', author='$author', 
             price='$price', description='$description', image='$image' 
             WHERE id=$id");

    } else {

        mysqli_query($conn, 
            "UPDATE books SET title='$title', author='$author', 
             price='$price', description='$description' 
             WHERE id=$id");
    }

    header("Location: manage_books.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Book</title>
</head>
<body>

<h2>Edit Book</h2>

<form method="POST" enctype="multipart/form-data">

Title:<br>
<input type="text" name="title" value="<?php echo $book['title']; ?>" required><br><br>

Author:<br>
<input type="text" name="author" value="<?php echo $book['author']; ?>" required><br><br>

Price:<br>
<input type="number" step="0.01" name="price" value="<?php echo $book['price']; ?>" required><br><br>

Description:<br>
<textarea name="description"><?php echo $book['description']; ?></textarea><br><br>

Current Image:<br>
<img src="../uploads/<?php echo $book['image']; ?>" width="80"><br><br>

Change Image:<br>
<input type="file" name="image"><br><br>

<button type="submit" name="update_book">Update Book</button>

</form>

<br>
<a href="manage_books.php">Back</a>

</body>
</html>