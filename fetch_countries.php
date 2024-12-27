<?php
include 'db.php';


$query = "SELECT country_id, country_name FROM countries";
$result = mysqli_query($conn, $query);

$countries = array();
while ($row = mysqli_fetch_assoc($result)) {
    $countries[] = $row;
}


echo json_encode(['countries' => $countries]);

mysqli_close($conn); 
?>
