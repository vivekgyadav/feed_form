$(document).ready(function () {

    $.ajax({
        url: 'fetch_countries.php', 
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            const countries = data.countries;
            countries.forEach(function (country) {
                $('#country').append(`<option value="${country.country_id}">${country.country_name}</option>`);
            });
        },
        error: function () {
            alert("Error fetching countries.");
        }
    });

    
    $('#country').change(function () {
        const countryId = $(this).val();
        $('#state').empty().append('<option value="">Select State</option>').prop('disabled', true);
        $('#city').empty().append('<option value="">Select City</option>').prop('disabled', true);

        if (countryId) {
            $.ajax({
                url: 'fetch_states.php', 
                type: 'POST',
                data: { country_id: countryId },
                dataType: 'json',
                success: function (data) {
                    const states = data.states;
                    states.forEach(function (state) {
                        $('#state').append(`<option value="${state.state_id}">${state.state_name}</option>`);
                    });
                    $('#state').prop('disabled', false);
                },
                error: function () {
                    alert("Error fetching states.");
                }
            });
        }

        
        validateCountry();
    });

    
    $('#state').change(function () {
        const stateId = $(this).val();
        $('#city').empty().append('<option value="">Select City</option>').prop('disabled', true);

        if (stateId) {
            $.ajax({
                url: 'fetch_cities.php', 
                type: 'POST',
                data: { state_id: stateId },
                dataType: 'json',
                success: function (data) {
                    const cities = data.cities;
                    cities.forEach(function (city) {
                        $('#city').append(`<option value="${city.city_id}">${city.city_name}</option>`);
                    });
                    $('#city').prop('disabled', false);
                },
                error: function () {
                    alert("Error fetching cities.");
                }
            });
        }

        
        validateState();
    });

    
    $('#city').change(function () {
        
        validateCity();
    });

    
    $('input[name="gender"]').change(function () {
        validateGender();
    });

    
    $('input[name="nationality"]').change(function () {
        validateNationality();
    });

    
    function validateCountry() {
        if ($("#country").val().trim() == "") {
            $("#country-error").html("*Please select Country");
            return false;
        } else {
            $("#country-error").html("");
            return true;
        }
    }

    function validateState() {
        if ($("#state").val().trim() == "") {
            $("#state-error").html("*Please select State");
            return false;
        } else {
            $("#state-error").html("");
            return true;
        }
    }

    function validateCity() {
        if ($("#city").val().trim() == "") {
            $("#city-error").html("*Please select City");
            return false;
        } else {
            $("#city-error").html("");
            return true;
        }
    }

    function validateGender() {
        if (!$('input[name="gender"]:checked').val()) {
            $('#gender-error').text('Please select your gender.');
            return false;
        } else {
            $('#gender-error').text('');
            return true;
        }
    }

    function validateNationality() {
        const selectedNationalities = $('input[name="nationality"]:checked').length;
        if (selectedNationalities === 0) {
            $('#nationality-error').text('Please select at least one nationality.');
            return false;
        } else {
            $('#nationality-error').text('');
            return true;
        }
    }

    
    function checkUniquePhone(phone, callback) {
        $.ajax({
            url: 'phone_function.php',
            type: 'POST',
            data: { action: 'check_phone', phone: phone },
            success: function (response) {
                callback(response === 'unique');
            },
            error: function () {
                alert("Error checking phone number.");
                callback(false);
            }
        });
    }

   

    
    $("#myForm").submit(function (e) {
        e.preventDefault();
        let formElement=$(this)[0]==document.getElementById("myForm")?$(this)[0]:document.getElementById("myForm");
       
        
        let isValid = true;

        $(".error").html("");  

        
        isValid &= validateCountry();
        isValid &= validateState();
        isValid &= validateCity();
        isValid &= validateGender();
        isValid &= validateNationality();

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

        if ($("#feedback").val().trim() == "") {
            isValid = false;
            $("#feedback-error").html("*Feedback is required");
        }

        let phone = $("#phone").val().trim();
        if (phone == "") {
            isValid = false;
            $("#phone-error").html("*Phone is required.");
        } else if (!/^\d{10}$/.test(phone)) {
            isValid = false;
            $("#phone-error").html("*Phone must be a 10-digit number.");
        } else {
            checkUniquePhone(phone, function (isUnique) {
                if (!isUnique) {
                    isValid = false;
                    $("#phone-error").html("*Phone number already exists!");
                }

                if (isValid) {
                    $("#loader").show();
                    console.log(formElement);
                    
                        let formData=new FormData(formElement);
                    $.ajax({
                        url: 'add_function.php',
                        type: 'POST',
                        processData:false,
                        contentType:false,
                        data: formData,
                        success: function (response) {
                            alert(response); 
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
        }
    });
});
