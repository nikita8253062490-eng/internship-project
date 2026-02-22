<?php
session_start();
include '../config.php';

if($_SESSION['user_role'] != 'admin') {
    echo "Access Denied!";
    exit();
}

$sql = "SELECT * FROM books";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Books</title>
</head>
<body>

<h2>Manage Books</h2>

<a href="add_book.php">Add New Book</a><br><br>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Author</th>
        <th>Price</th>
        <th>Image</th>
        <th>Action</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $row['author']; ?></td>
            <td><?php echo $row['price']; ?></td>
            <td>
                <img src="../uploads/<?php echo $row['image']; ?>" width="60">
            </td>
            <td>
                <a href="edit_book.php?id=<?php echo $row['id']; ?>">Edit</a> |
<a href="delete_book.php?id=<?php echo $row['id']; ?>" 
   onclick="return confirm('Are you sure you want to delete this book?')">
   Delete
</a>
            </td>
        </tr>
    <?php } ?>

</table>

<br>
<a href="admin_dashboard.php">Back to Dashboard</a>

</body>
</html>