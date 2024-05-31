<?php
include("../database.php");
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit;
}

$email = $_SESSION['email'];

$username_query = "SELECT username FROM users WHERE email = '$email'";
$password_query = "SELECT password FROM users WHERE email = '$email'";
$id_query = "SELECT id FROM users WHERE email = '$email'";
$role_query = "SELECT role FROM users WHERE email = '$email'";

$username_result = mysqli_query($conn, $username_query);
$password_result = mysqli_query($conn, $password_query);
$id_result = mysqli_query($conn, $id_query);
$role_result = mysqli_query($conn, $role_query);

$username_row = mysqli_fetch_assoc($username_result);
$password_row = mysqli_fetch_assoc($password_result);
$id_row = mysqli_fetch_assoc($id_result);
$role_row = mysqli_fetch_assoc($role_result);

$username = $username_row['username'];
$id = $id_row['id'];
$role = $role_row['role'];
$password = $password_row['password'];

$interest_query = "SELECT interest FROM users WHERE id = '$id'";
$interest_result = mysqli_query($conn, $interest_query);
$interest_row = mysqli_fetch_assoc($interest_result);
$interest = $interest_row ? $interest_row['interest'] : 'You have not picked an interest';


?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profileStudent.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Profile</title>
</head>

<body>
    <div class="navbar">
        <div class="image">
            <img src="../BelajarQ.png" alt="logo">
        </div>
        <div class="profile-button">
            <i class="fa-solid fa-user" onclick="window.location.href='profileStudent.php';"></i>
        </div>
    </div>
    <div class="container">
        <div class="back-to-dashboard">
            <button onclick="window.location.href='dashboardStudent.php';">Back to <br><span class="bold">Dashboard</span></button>
        </div>
        <div class="profile-container">
            <div class="profile-setting">
                <h2>Profile Settings</h2>
            </div>
            <div class="profile">
                <div class="content">
                    <h2>Email</h2>
                    <p><?php echo htmlspecialchars($email); ?></p>
                    <h2>Username</h2>
                    <p><?php echo htmlspecialchars($username); ?></p>
                    <h2>Password</h2>
                    <p><?php echo htmlspecialchars($password); ?></p>
                    <h2>Interest:</h2>
                    <h3><?php echo htmlspecialchars($interest); ?></h3>
                    <button class="logout" onclick="logout()">Log Out</button>
                    <button class="edit" onclick= "window.location.href='editProfile.php';">Edit Profile</button>
                    <script>
                        function logout() {
                            window.location.href = '../login.php';
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</body>

</html>