<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $country_id = mysqli_real_escape_string($conn, $_POST['country']);
    $state_id = mysqli_real_escape_string($conn, $_POST['state']);
    $city_id = mysqli_real_escape_string($conn, $_POST['city']);
    $phone = preg_replace("/[^0-9]/", "", $_POST['phone']);  
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $nationality = isset($_POST['nationality']) ? mysqli_real_escape_string($conn, $_POST['nationality']) : '';
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);

    // Fetch country, state, and city names
    $country_query = mysqli_query($conn, "SELECT country_name FROM countries WHERE country_id = '$country_id'");
    $country_name = mysqli_fetch_assoc($country_query)['country_name'];

    $state_query = mysqli_query($conn, "SELECT state_name FROM states WHERE state_id = '$state_id'");
    $state_name = mysqli_fetch_assoc($state_query)['state_name'];

    $city_query = mysqli_query($conn, "SELECT city_name FROM cities WHERE city_id = '$city_id'");
    $city_name = mysqli_fetch_assoc($city_query)['city_name'];

    $file_name = '';
    $uploadOk = 1;

    // Handle file upload

    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $file_name = basename($_FILES['file']['name']); 
        $target_file = $target_dir . $file_name;

        if ($_FILES['file']['size'] > 2000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        $allowed_types = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (!in_array($imageFileType, $allowed_types)) {
            echo "Sorry, only JPG, JPEG, PNG, PDF, DOC, and DOCX files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (!move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
                echo "Sorry, there was an error uploading your file.";
                $uploadOk = 0; 
            }
        }
    } else {
        echo "No file uploaded or there was an upload error.";
        $uploadOk = 0; 
    }

    
    if ($uploadOk == 1) {
        
        $stmt = $conn->prepare("INSERT INTO mx_feed_frm (name, email, address, country, state, city, phone, gender, nationality, feedback, file_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssss", $name, $email, $address, $country_name, $state_name, $city_name, $phone, $gender, $nationality, $feedback, $file_name);
        
        if ($stmt->execute()) {
            echo "New record added successfully";
        } else {
            error_log("SQL Error: " . $stmt->error);
            echo "Error: " . $stmt->error;
        }
        
        $stmt->close();
    }

    
    $conn->close();
}
?>
