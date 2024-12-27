<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    
    $sql = "SELECT * FROM mx_feed_frm WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $record = mysqli_fetch_assoc($result);
    } else {
        echo "Record not found!";
        exit;
    }
} else {
    echo "Invalid request!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $country_id = mysqli_real_escape_string($conn, $_POST['country']);
    $state_id = mysqli_real_escape_string($conn, $_POST['state']);
    $city_id = mysqli_real_escape_string($conn, $_POST['city']);
    $phone = preg_replace("/[^0-9]/", "", $_POST['phone']);  
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $nationality = isset($_POST['nationality']) ? implode(',', $_POST['nationality']) : '';
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);

    
    $country_query = mysqli_query($conn, "SELECT country_name FROM countries WHERE country_id = '$country_id'");
    $country_name = mysqli_fetch_assoc($country_query)['country_name'];

    $state_query = mysqli_query($conn, "SELECT state_name FROM states WHERE state_id = '$state_id'");
    $state_name = mysqli_fetch_assoc($state_query)['state_name'];

    $city_query = mysqli_query($conn, "SELECT city_name FROM cities WHERE city_id = '$city_id'");
    $city_name = mysqli_fetch_assoc($city_query)['city_name'];

    
    $sql = "UPDATE mx_feed_frm SET 
            name = '$name', 
            email = '$email', 
            address = '$address', 
            country = '$country_name', 
            state = '$state_name', 
            city = '$city_name', 
            phone = '$phone', 
            gender = '$gender', 
            nationality = '$nationality', 
            feedback = '$feedback' 
            WHERE id = $id";

            if (mysqli_query($conn, $sql)) {
                echo "Record updated successfully!";
                exit; 
            } else {
                echo "Error updating record: " . mysqli_error($conn);
                exit; 
            }

}

// Get the current record data for displaying in the form
$country_value = isset($record['country']) ? htmlspecialchars($record['country']) : '';
$state_value = isset($record['state']) ? htmlspecialchars($record['state']) : '';
$city_value = isset($record['city']) ? htmlspecialchars($record['city']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title> 
    <script src="js/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="record.css">
</head>
<body>
  <div id="out-box">
    <h1>Edit Feedback Form!</h1>
    <div id="mid-box">
        <div id="inn-box">
            <form id="myForm" method="post" action="">
                <label for="name">Name*:</label><br>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($record['name']); ?>">
                <span class="error" id="name-error" style="color: red;"></span><br>

                <label for="email">Email*:</label><br>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($record['email']); ?>">
                <span class="error" id="email-error" style="color: red;"></span><br>

                <label for="address">Address:</label><br>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($record['address']); ?>"><br>

                <label for="country">Country*:</label><br>
                <select id="country" name="country">
                    <option value="">Select a country</option>
                </select>
                <span class="error" id="country-error" style="color: red;"></span><br>

                <label for="state">State*:</label><br>
                <select id="state" name="state">
                    <option value="">Select a state</option>
                </select>
                <span class="error" id="state-error" style="color: red;"></span><br>

                <label for="city">City*:</label><br>
                <select id="city" name="city">
                    <option value="">Select a city</option>
                </select>
                <span class="error" id="city-error" style="color: red;"></span><br>

                <label for="phone">Phone No*:</label><br>
                <input type="number" id="phone" name="phone" value="<?php echo htmlspecialchars($record['phone']); ?>">
                <span class="error" id="phone-error" style="color: red;"></span><br>

                <label>Gender*:</label><br>
                <div class="gender">
                    <label><input type="radio" name="gender" value="male" <?php if ($record['gender'] == 'male') echo 'checked'; ?>> Male</label>
                    <label><input type="radio" name="gender" value="female" <?php if ($record['gender'] == 'female') echo 'checked'; ?>> Female</label>
                    <label><input type="radio" name="gender" value="other" <?php if ($record['gender'] == 'other') echo 'checked'; ?>> Other</label>
                </div>
                <span class="error" id="gender-error" style="color: red;"></span><br>

                <label>Nationality:</label>
                <div class="nationality">
                    <?php $nationalities = explode(",", $record['nationality']); ?>
                    <label><input type="checkbox" name="nationality[]" value="indian" <?php if (in_array('indian', $nationalities)) echo 'checked'; ?>> Indian</label>
                    <label><input type="checkbox" name="nationality[]" value="non-indian" <?php if (in_array('non-indian', $nationalities)) echo 'checked'; ?>> Non-Indian</label>
                </div>
                <span class="error" id="nationality-error" style="color: red;"></span><br>

                <label for="feedback">Feedback*:</label><br>
                <textarea id="feedback" name="feedback"><?php echo htmlspecialchars($record['feedback']); ?></textarea><div id="loader"></div>
                <span class="error" id="feedback-error" style="color: red;"></span><br>

                <div class="button-container">
                    <button type="submit" class="submit-button">Submit</button>
                </div>
            </form>
            <div>
                <a href="index.php">Back to Home page</a>
            </div>    
        </div>
    </div>
  </div>

  <script>
$(document).ready(function () {
    
    function fetchCountries() {
        $.ajax({
            url: 'fetch_countries.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                const countries = data.countries;
                countries.forEach(function (country) {
                    const selected = country.country_name === "<?php echo $country_value; ?>" ? "selected" : "";
                    $('#country').append(`<option value="${country.country_id}" ${selected}>${country.country_name}</option>`);
                });
                
                if ($('#country').val()) {
                    fetchStates($('#country').val());
                }
            }
        });
    }

    function fetchStates(countryId) {
        if (countryId) {
            $.ajax({
                url: 'fetch_states.php',
                type: 'POST',
                data: { country_id: countryId },
                dataType: 'json',
                success: function (data) {
                    const states = data.states;
                    $('#state').empty().append('<option value="">Select State</option>');
                    states.forEach(function (state) {
                        const selected = state.state_name === "<?php echo $state_value; ?>" ? "selected" : "";
                        $('#state').append(`<option value="${state.state_id}" ${selected}>${state.state_name}</option>`);
                    });
                    
                    if ($('#state').val()) {
                        fetchCities($('#state').val());
                    }
                }
            });
        }
    }

    function fetchCities(stateId) {
        if (stateId) {
            $.ajax({
                url: 'fetch_cities.php',
                type: 'POST',
                data: { state_id: stateId },
                dataType: 'json',
                success: function (data) {
                    const cities = data.cities;
                    $('#city').empty().append('<option value="">Select City</option>');
                    cities.forEach(function (city) {
                        const selected = city.city_name === "<?php echo $city_value; ?>" ? "selected" : "";
                        $('#city').append(`<option value="${city.city_id}" ${selected}>${city.city_name}</option>`);
                    });
                }
            });
        }
    }

    $('#country').change(function () {
        fetchStates($(this).val());
    });

    $('#state').change(function () {
        fetchCities($(this).val());
    });

    fetchCountries();

    $("#myForm").submit(function (e) {
        e.preventDefault();

        let isValid = true;

        $(".error").html("");  

        
        const namePattern = /^[a-zA-Z\s]+$/;
        const name = $('#name').val().trim();
        if (!namePattern.test(name)) {
            $('#name-error').text('*Name must contain only letters.');
            isValid = false;
        }

        let email = $("#email").val().trim();
        function validateEmail() {
            var re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            return re.test(email);
        }
        if (email === "") {
            isValid = false;
            $("#email-error").html("Email is required.");
        } else if (!validateEmail(email)) {
            isValid = false;
            $("#email-error").html("Invalid email format.");
        }

        if ($("#country").val() === "") {
            isValid = false;
            $("#country-error").html("*Country is required.");
        }

        if ($("#state").val() === "") {
            isValid = false;
            $("#state-error").html("*State is required.");
        }

        if ($("#city").val() === "") {
            isValid = false;
            $("#city-error").html("*City is required.");
        }

        let phone = $("#phone").val().trim();
        if (phone === "") {
            isValid = false;
            $("#phone-error").html("Phone is required.");
        } else if (!/^\d{10}$/.test(phone)) {
            isValid = false;
            $("#phone-error").html("Phone must be a 10-digit number.");
        }

        if ($("input[name='gender']:checked").length === 0) {
            isValid = false;
            $("#gender-error").html("*Gender is required.");
        }

        if ($("input[name='nationality[]']:checked").length === 0) {
            $("#nationality-error").html("*At least one nationality is required.");
            isValid = false;
        }

        if ($("#feedback").val().trim() === "") {
            isValid = false;
            $("#feedback-error").html("*Feedback is required.");
        }

        if (isValid) {
            $("#loader").show();
            $.ajax({
                url: 'edit.php?id=<?php echo $id; ?>', 
                type: 'POST',
                data: $("#myForm").serialize(),
                success: function (response) {
                    alert("Record updated successfully!"); 
                    $("#loader").hide();
                    $("#myForm")[0].reset();
                    $('#state').prop('disabled', true);
                    $('#city').prop('disabled', true);
                },
                error: function () {
                    alert("Error found! Please try again later");
                    $("#loader").hide();
                }
            });
        }
    });
});
  </script>
</body>
</html>

<?php
mysqli_close($conn); 
?>
