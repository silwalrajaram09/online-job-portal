$(document).ready(function () {
    
    $("form").submit(function (e) {
        e.preventDefault();
       
        var actionType = $("input[name='action']").val();
        var actionUrl = "";
        if (actionType === "login") {
            actionUrl = "/ojs/backend/controllers/authcontroller.php?action=login"; 
        } else if (actionType === "registerJobseeker") {
            actionUrl = "/ojs/backend/controllers/authcontroller.php?action=registerJobseeker"; 
        } else if (actionType === "registerCompany") {
            actionUrl = "/ojs/backend/controllers/authcontroller.php?action=registerCompany"; 
        }

        $.ajax({
            url: actionUrl, 
            method: "POST",
            data: $(this).serialize(), 
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#Message").html("<span style='color:green;'>" + response.message + "</span>");
                    $("#Form")[0].reset(); 
                    
                   
                    if (actionType === "login") {
                        setTimeout(function () {
                            window.location.href = response.redirect; 
                        }, 1000); 
                    }
                } else {
                    $("#Message").html("<span style='color:red;'>" + response.message + "</span>");
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error, xhr.responseText); 
                $("#Message").html("<span style='color:red;'>An unexpected error occurred. Please try again later.</span>");
            }
        });
    });
    // $("#forgetPasswordLink").click(function (e) {
    //     e.preventDefault();
        
    //     var email = $("#email").val().trim(); 
    //     if (email === "") {
    //         $("#Message").html("<span style='color:red;'>Please enter your email.</span>");
    //         return;
    //     }

    //     $.ajax({
    //         url: "/ojs/backend/controllers/authcontroller.php?action=verifyEmail",
    //         method: "POST",
    //        /// data: { email: email },
    //         dataType: "json",
    //         success: function (response) {
    //             //console.log(response);
    //             if (response.success) {
                   
    //                 $("#resetEmail").val(email);
    //                 $("#resetPasswordModal").show(); // Show reset password form
    //             } else {
    //                 $("#Message").html("<span style='color:red;'>" + response.message + "</span>");
    //             }
    //         },
    //         error: function (xhr, status, error) {
    //             console.error("AJAX Error:", status, error, xhr.responseText);
    //             $("#Message").html("<span style='color:red;'>Error: " + xhr.responseText + "</span>");
    //         }
            
    //     });
     });

    // Step 2: Reset password
    $(document).ready(function() {
        $("#forgetPasswordLink").click(function() {
            var email = $("#email").val();
            if (!isValidEmail(email)) {
                $("#Message").html("<span style='color:red;'>Please enter a valid email address.</span>");
                return;
            }
    
            $.ajax({
                url: "/ojs/backend/controllers/forgetPassword.php?action=verifyEmail",
                method: "POST",
                data: { email: email },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#resetEmail").val(email);
                        $("#resetPasswordModal").show();
                        $("#Message").html(""); // Clear previous message
                    } else {
                        $("#Message").html("<span style='color:red;'>" + response.message + "</span>");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error, xhr.responseText);
                    try {
                        const parsedResponse = JSON.parse(xhr.responseText);
                        $("#Message").html("<span style='color:red;'>Error: " + parsedResponse.message + "</span>");
                    } catch (e) {
                        $("#Message").html("<span style='color:red;'>An unexpected error occurred.</span>");
                    }
                }
            });
        });
    
        $("#resetPasswordBtn").click(function() {
            //var email = $("#resetEmail").val();
            var newPassword = $("#newPassword").val();
            var confirmPassword = $("#confirmPassword").val();
            
            if (newPassword === "" || confirmPassword === "") {
                $("#resetMessage").html("<span style='color:red;'>Please fill in all fields.</span>");
                return; // Stop execution
            }
            
            if (newPassword.length < 8) {
                $("#resetMessage").html("<span style='color:red;'>Password must be at least 8 characters long.</span>");
                return; // Stop execution
            }
            
            if (newPassword !== confirmPassword) {
                $("#resetMessage").html("<span style='color:red;'>Passwords do not match.</span>");
                return; // Stop execution
            }
    
            $.ajax({
                url: "/ojs/backend/controllers/forgetPassword.php?action=resetPassword",
                method: "POST",
                data: { newPassword: newPassword },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#resetMessage").html("<span style='color:green;'>" + response.message + "</span>");
                        setTimeout(function() {
                            $("#resetPasswordModal").hide();
                            $("#resetMessage").html(""); 
                            $("#newPassword").val(""); 
                            $("#confirmPassword").val("");
                        }, 2000);
                    } else {
                        $("#resetMessage").html("<span style='color:red;'>" + response.message + "</span>");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error, xhr.responseText);
                    try {
                        const parsedResponse = JSON.parse(xhr.responseText);
                        $("#resetMessage").html("<span style='color:red;'>Error: " + parsedResponse.message + "</span>");
                    } catch (e) {
                        $("#resetMessage").html("<span style='color:red;'>An unexpected error occurred.</span>");
                    }
                }
            });
        });
        $("#closeModal").click(function() {
            $("#resetPasswordModal").hide();
        });
        function isValidEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }
    });
