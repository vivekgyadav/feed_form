<?php
include 'db.php'; 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add new record</title> 
    <script src="js/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="record.css">
</head>
<body>

  <div id="out-box">
    <h1>Feedback Form!</h1>
    <div id="mid-box">
        <div id="inn-box">
            <form id="myForm" method="post" action="" enctype="multipart/form-data">
                <label for="name">Name*:</label><br>
                <input type="text" id="name" name="name">
                <span class="error" id="name-error" style="color: red;"></span><br>

                <label for="email">Email*:</label><br>
                <input type="text" id="email" name="email">
                <span class="error" id="email-error" style="color: red;"></span><br>

                <label for="address">Address:</label><br>
                <input type="text" id="address" name="address"><br>

                <label for="country">Country*:</label><br>
                <select id="country" name="country" >
                    <option value="">Select a country</option>
                </select>
                <span class="error" id="country-error" style="color: red;"></span><br>

                <label for="state">State*:</label><br>
                <select id="state" name="state" disabled>
                    <option value="">Select a state</option>
                </select>
                <span class="error" id="state-error" style="color: red;"></span><br>

                <label for="city">City*:</label><br>
                <select id="city" name="city" disabled>
                    <option value="">Select a City</option>
                </select>
                <span class="error" id="city-error" style="color: red;"></span><br>

                <label for="phone">Phone No*:</label><br>
                <input type="number" id="phone" name="phone">
                <span class="error" id="phone-error" style="color: red;"></span><br>

                <label>Gender*:</label><br>
                <div class="gender">
                    <label><input type="radio" name="gender" value="male"> Male</label>
                    <label><input type="radio" name="gender" value="female"> Female</label>
                    <label><input type="radio" name="gender" value="other"> Other</label>
                </div>
                <span class="error" id="gender-error" style="color: red;"></span><br>

                <label>Nationality:</label>
                <div class="nationality">
                    <label><input type="checkbox" name="nationality" value="indian"> Indian</label>
                    <label><input type="checkbox" name="nationality" value="non-indian"><br>Non-Indian</label>
                </div>
                <span class="error" id="nationality-error" style="color: red;"></span><br>

                <label for="feedback">Feedback*:</label><br>
                <textarea id="feedback" name="feedback"></textarea> <div id="loader"></div>
                <span class="error" id="feedback-error" style="color: red;"></span><br>

                <label for="file">Upload File:</label><br>
                <input type="file" id="mFile" name="file"><br><br>

                <div class="button-container">
                    <button type="submit" class="submit-button">Submit</button>
                    <button type="reset" class="reset-button">Reset</button>
                </div>
            </form>
            <div>
                <a href="index.php">Back to Home page</a>
            </div>    
        </div>
    </div>
  </div>

  <script src="app.js"></script>
</body>
</html>

<?php
mysqli_close($conn); 
?>
