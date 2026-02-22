<?php
session_start();
include 'config.php';

// Pagination settings
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

if(isset($_GET['search']) && $_GET['search'] != "") {

    $search_input = $_GET['search'];
    $search = "%" . $search_input . "%";

    $stmt = mysqli_prepare($conn,
        "SELECT * FROM books 
         WHERE title LIKE ? OR author LIKE ?
         ORDER BY created_at DESC
         LIMIT ?, ?");

    mysqli_stmt_bind_param($stmt, "ssii", $search, $search, $start, $limit);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Count total results
    $count_stmt = mysqli_prepare($conn,
        "SELECT COUNT(*) as total FROM books 
         WHERE title LIKE ? OR author LIKE ?");
    mysqli_stmt_bind_param($count_stmt, "ss", $search, $search);
    mysqli_stmt_execute($count_stmt);
    $count_result = mysqli_stmt_get_result($count_stmt);
    $total = mysqli_fetch_assoc($count_result)['total'];

} else {

    $sql = "SELECT * FROM books 
            ORDER BY created_at DESC 
            LIMIT $start, $limit";
    $result = mysqli_query($conn, $sql);

    $count_sql = "SELECT COUNT(*) as total FROM books";
    $count_result = mysqli_query($conn, $count_sql);
    $total = mysqli_fetch_assoc($count_result)['total'];
}

$total_pages = ceil($total / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Bookstore</title>
</head>
<body>

<h2>Online Bookstore</h2>

<form method="GET" action="">
    <input type="text" name="search" placeholder="Search by title or author"
           value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
    <button type="submit">Search</button>
</form>
<br>

<?php if(isset($_SESSION['user_id'])) { ?>
    Welcome <?php echo $_SESSION['user_name']; ?> |
    <a href="cart.php">View Cart</a> |
    <a href="my_orders.php">My Orders</a> |
    <a href="logout.php">Logout</a>
<?php } else { ?>
    <a href="login.php">Login</a> |
    <a href="register.php">Register</a>
<?php } ?>

<hr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
        <h3><?php echo $row['title']; ?></h3>
        <img src="uploads/<?php echo $row['image']; ?>" width="120"><br>
        <strong>Author:</strong> <?php echo $row['author']; ?><br>
        <strong>Price:</strong> ₹<?php echo $row['price']; ?><br>
        <p><?php echo $row['description']; ?></p>

        <?php if(isset($_SESSION['user_id'])) { ?>
            <a href="add_to_cart.php?id=<?php echo $row['id']; ?>">
                <button>Add to Cart</button>
            </a>
        <?php } ?>

    </div>

<?php } ?>

<hr>

<?php if($total_pages > 1) { ?>

    <?php if($page > 1) { ?>
        <a href="?page=<?php echo $page-1; ?><?php if(isset($_GET['search'])) echo '&search='.$_GET['search']; ?>">
            Previous
        </a>
    <?php } ?>

    Page <?php echo $page; ?> of <?php echo $total_pages; ?>

    <?php if($page < $total_pages) { ?>
        <a href="?page=<?php echo $page+1; ?><?php if(isset($_GET['search'])) echo '&search='.$_GET['search']; ?>">
            Next
        </a>
    <?php } ?>

<?php } ?>

</body>
</html>