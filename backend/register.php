<?php
include("database.php");
$errorMessage = '';
$registrationSuccess = false; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL); 
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    $role = filter_input(INPUT_POST, "role", FILTER_SANITIZE_SPECIAL_CHARS);

    $check = "SELECT username FROM users WHERE username = '$username'";
    $checkmail = "SELECT email FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $check);
    $resultmail = mysqli_query($conn, $checkmail);
    if (mysqli_num_rows($result) > 0) {
        $errorMessage = "Username already taken.";
    } else if(mysqli_num_rows($resultmail) > 0){
        $errorMessage = "Email already in use.";
    } else {
    $sql = "INSERT INTO users(username, email, password, role)
            VALUES ('$username', '$email', '$password', '$role')";
    mysqli_query($conn, $sql);
    $registrationSuccess = true;
    }
}

mysqli_close($conn);

if ($registrationSuccess) {
    header("Location: success.php"); 
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="register.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="welcome-section">
            <img src="study2.png" alt="Study" class="study-image">
            <p>Limitless learning opportunity.</p>
        </div>
        <div class="login-section">
            <img src="BelajarQ.png" alt="BelajarQ Logo" class="logo">
            <form action="" method="post">
                
                <label for="email">Email<span class="required">*</span></label><br>
                <input type="text" id="email" name="email" placeholder="Enter your email" required><br>
                <label for="username">Username<span class="required">*</span></label><br>
                <input type="text" id="username" name="username" placeholder="Create a username" required><br>
                <div class="password-container">
                    <label for="password">Password<span class="required">*</span></label><br>
                    <div class="password-input">
                        <input type="password" id="password" name="password" placeholder="Create a password" required>
                        <span class="toggle-password" onclick="togglePasswordVisibility()">
                            <i class="fa-solid fa-eye" aria-hidden="true" id="eye"></i>
                        </span>
                    </div>
                    <script>
                        var state = false;
                        function togglePasswordVisibility(){
                            if (state) {
                                document.getElementById("password").setAttribute("type", "password");
                                state = false;
                            }
                            else{
                                document.getElementById("password").setAttribute("type", "text");
                                state = true;
                            }
                        }
                    </script>
                </div>
                <div class="role-select">
                    <select name="role">
                        <option value="teacher">Teacher</option>
                        <option value="student">Student</option>
                    </select>
                    <span class="toggle-dropdown">
                        <i class="fa-solid fa-chevron-down" aria-hidden="true" id="dropdown"></i>
                    </span>
                </div>
                <?php if (!empty($errorMessage)): ?>
                <p class="error-message"><?= $errorMessage; ?></p>
                <?php endif; ?>
                <input type="submit" name="submit" value="Sign Up">
            </form>
            <p>Already have an account? <a href="login.php" id="sign-up-link">Sign In</a></p>
        </div>
    </div>
</body>
</html>