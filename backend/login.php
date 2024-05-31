<?php
session_start();
include("database.php");
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $row['role']; 

        if ($row['role'] == 'teacher') {
            header("Location: Teacher/dashboardTeacher.php");
        } else if ($row['role'] == 'student') {
            header("Location: Student/dashboardStudent.php");
        }
        exit;
    } else {
        $errorMessage = "Incorrect email or password.";
    }
}
mysqli_close($conn); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BelajarQ</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="welcome-section">
            <img src="study.png" alt="Study" class="study-image">
            <p>We ensure your knowledge.</p>
        </div>
        <div class="login-section">
            <img src="BelajarQ.png" alt="BelajarQ Logo" class="logo">
            <form action="" method="post">
                <label for="email">Email<span class="required">*</span></label><br>
                <input type="text" id="email" name="email" placeholder="Enter your email" required><br>
                <div class="password-container">
                    <label for="password">Password<span class="required">*</span></label><br>
                    <div class="password-input">
                        <input type="password" id="password" name="password" placeholder="Enter password" required>
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
                <input type="submit" name="submit" value="Sign In">
                <?php if (!empty($errorMessage)): ?>
                <p class="error-message"><?= $errorMessage; ?></p>
                <?php endif; ?>
            </form>
            <p>Don't have an account? <a href="register.php" id="sign-up-link">Sign Up</a></p>
        </div>
    </div>
</body>
</html>


