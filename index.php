<?php
include 'db.php';

$sql = "SELECT * FROM mx_feed_frm";
$result = mysqli_query($conn, $sql);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PS Engineering</title>
    
    <script src="js/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    

<div id="out-box">
    <h1>Feedback List</h1>

    <div class="button-container">
        <a href="add.php" class="submit-button">Add New Record</a>
    </div>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Country</th>
                <th>State</th>
                <th>City</th>
                <th>Phone</th>
                <th>Gender</th>
                <th>Nationality</th>
                <th>Feedback</th>
                <th>File</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo $row['country']; ?></td>
                        <td><?php echo $row['state']; ?></td>
                        <td><?php echo $row['city']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><?php echo $row['gender']; ?></td>
                        <td><?php echo $row['nationality']; ?></td>
                        <td><?php echo $row['feedback']; ?></td>
                        <td>
                            <?php if (!empty($row['file_name'])): ?>
                                <a href="uploads/<?php echo $row['file_name']; ?>" class="file-link" download><?php echo $row['file_name']; ?></a>
                            <?php else: ?>
                                No File
                            <?php endif; ?>
                         </td>
                    
                        <td>
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="edit-button">Edit</a>
                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="12">No records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php mysqli_close($conn);?>