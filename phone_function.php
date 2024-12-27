<?php
include 'db.php';

if (isset($_POST['action']) && $_POST['action'] == 'check_phone') {
    $phone = preg_replace("/[^0-9]/", "", $_POST['phone']);  

    $sql = "SELECT * FROM mx_feed_frm WHERE phone = '$phone'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo 'exists';
    } else {
        echo 'unique';
    }
    exit;  
}

?>