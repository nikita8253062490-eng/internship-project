<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['user_id'];

if(isset($_POST['update'])) {

    $name = $_POST['name'];

    // Image upload
    if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {

        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['profile_pic']['name'];
        $filesize = $_FILES['profile_pic']['size'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if(in_array($ext, $allowed) && $filesize <= 2000000) {

            $newname = time() . "." . $ext;
            move_uploaded_file($_FILES['profile_pic']['tmp_name'], "uploads/" . $newname);

            $stmt = mysqli_prepare($conn, 
                "UPDATE users SET name=?, profile_pic=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, "ssi", $name, $newname, $id);
            mysqli_stmt_execute($stmt);

        }
    } else {
        $stmt = mysqli_prepare($conn, 
            "UPDATE users SET name=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "si", $name, $id);
        mysqli_stmt_execute($stmt);
    }

    header("Location: profile.php");
    exit();
}

$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
</head>
<body>

<h2>My Profile</h2>

<?php if($user['profile_pic']) { ?>
    <img src="uploads/<?php echo $user['profile_pic']; ?>" 
         width="120"><br><br>
<?php } ?>

<form method="POST" enctype="multipart/form-data">

    <label>Name:</label><br>
    <input type="text" name="name" 
           value="<?php echo $user['name']; ?>" required><br><br>

    <label>Profile Picture:</label><br>
    <input type="file" name="profile_pic"><br><br>

    <button type="submit" name="update">Update Profile</button>
</form>

<br>
<a href="dashboard.php">Back to Dashboard</a>

</body>
</html>