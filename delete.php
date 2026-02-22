<?php
include 'config.php';

if(isset($_GET['id'])) {

    $id = $_GET['id'];

    $sql = "DELETE FROM users WHERE id = $id";
    mysqli_query($conn, $sql);

    header("Location: users.php");
    exit();
}
?>