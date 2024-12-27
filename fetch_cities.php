<?php
include 'db.php'; 

$state_id = mysqli_real_escape_string($conn, $_POST['state_id']);

$query = "SELECT city_id, city_name FROM cities WHERE state_id = '$state_id'";
$result = mysqli_query($conn, $query);

$cities = array();
while ($row = mysqli_fetch_assoc($result)) {
    $cities[] = $row;
}


echo json_encode(['cities' => $cities]);

mysqli_close($conn); 
?>
