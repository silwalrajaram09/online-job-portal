
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/ojs/frontend/assets/css/register.css">
   
    </style>
</head>

<body>
    <div class="container">
        <h2>company Registration</h2>
        <form action="" method="post" id="Form">
        <input type="hidden" name="action" value="registerCompany">
        <div id="Message"></div>
            <h2>login information</h2>
            <hr>
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="text" id="email" name="email" value="">
                <p id="error"></p>
               
            </div>

           

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="*******" value="">
                
            </div>

          
            <h2>company information</h2>
            <hr>
            <div class="form-group">
                <label for="company_name">Comapany name</label>
                <input type="text" name="company_name" id="" class="form-control" placeholder="" aria-describedby="helpId" value="">
               

            </div>
            <div class="form-group">
                <label for="phone">Primary phone</label>
                <input type="tel" name="phone" id="phne" class="form-control" value="">
            </div>
            <div class="form-group">
                <label for="company_type">company type</label>
                <select name="company_type" id="company_type">
                    <!-- <option value="">selct company type</option>
                    <option value="itcompany">IT company</option>
                    <option value="ngo">NGO</option>
                    <option value="accounting">accounting</option>
                    <option value="advertising">advertising</option>
                    <option value="art">Art</option> -->
                </select>
               
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <select name="city" id="city">
                    <option value="">select location </option>
                    <option value="ktm">Kathmandu</option>
                    <option value="pokhara">pokhara</option>
                    <option value="lalitpur">lalitpur</option>

                </select>
                
            </div>
            <div class="form-group">
                <label for="location">location</label>
                <input type="text" name="location" id="location" value="">
               
            </div>
            <hr>
            <h2>contact person details</h2>
            <div class="form-group">
                <label for="contact_name">Contact person name</label>
                <input type="text" name="contact_name" id="name" value="">
              

            </div>
            <div class="form-group">
                <label for="mobile">Mobile</label>
                <input type="tel" name="mobile" id="mobile" value="">
               
            <div class="form-group">
                <label for="contact_email">Contact Person Email</label>
                <input type="text" name="contact_email" id="contact_email" value="">
                
            </div>
            <div class="form-group">

                <input type="submit" value="create account">
            </div>
        </form>
        <p>By creating an account with us, you're confirming that you've read our <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a></p>

        <p>Do you already have an account with us? <a href="../login">Click here to login</a></p>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="/ojs/frontend/assets/js/script.js"></script>

    <script>
        $(document).ready(function() {
                    // Fetch job categories dynamically via AJAX
                    $.ajax({
                        url: '../backend/controllers/user.php', // Modify this path if needed
                        method: 'GET',
                        data: {
                            action: 'fetch_all_company_type'
                        }, // Action to fetch all type
                        dataType: 'json',
                        success: function(response) {
                            let dropdown = $('#company_type');
                            dropdown.empty(); // Clear existing options
                            dropdown.append('<option value="">Select company type</option>'); // Default option

                            if (response.error) {
                                alert(response.error); // If there's an error
                            } else {
                                // Loop through each category and add it to the dropdown
                                response.forEach(function(category) {
                                    dropdown.append(
                                        `<option value="${category.id}">${category.type_name}</option>`
                                    );
                                });
                            }
                        },
                        error: function() {
                            alert('Failed to load categories.');
                        }
                    });
                     });
    </script>
</body>

</html>