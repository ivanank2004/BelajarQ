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

$interest_query = "SELECT interest_student FROM interest WHERE user_id = '$id'";
$interest_result = mysqli_query($conn, $interest_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>
    <h1> Profile </h1>
    <h2>Email: <?php echo htmlspecialchars($email); ?></h2>
    <h2>Username: <?php echo htmlspecialchars($username); ?></h2>
    <h2>Password: <?php echo htmlspecialchars($password); ?></h2>
    <h2>Id: <?php echo htmlspecialchars($id); ?></h2>
    <h2>Interests:</h2>
    <?php
    if (mysqli_num_rows($interest_result) > 0) {
        while ($interest_row = mysqli_fetch_assoc($interest_result)) {
            echo "<p>" . htmlspecialchars($interest_row['interest_student']) . "</p>";
        }
    } else {
        echo "<p>You haven't added an interest.</p>";
    }
    ?>
    <button onclick="window.location.href='dashboardStudent.php';">Back</button>
    <button onclick="logout()">Log Out</button>
    <script>
        function logout() {
            window.location.href = '../login.php';
        }
    </script>
    <button onclick="window.location.href='editProfile.php';">Edit Profile</button>
</body>
</html>