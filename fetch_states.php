<?php
include 'db.php'; 


$country_id = mysqli_real_escape_string($conn, $_POST['country_id']);


$query = "SELECT state_id, state_name FROM states WHERE country_id = '$country_id'";
$result = mysqli_query($conn, $query);

$states = array();
while ($row = mysqli_fetch_assoc($result)) {
    $states[] = $row;
}


echo json_encode(['states' => $states]);

mysqli_close($conn); 
?>
