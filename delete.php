<?php
include 'db.php';

$id = $_GET['id'];

$sql = "DELETE FROM mx_feed_frm WHERE id = $id";
if (mysqli_query($conn, $sql)) {
    echo "Record deleted successfully";
    header("Location: index.php"); 
} else {
    echo "Error deleting record: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
