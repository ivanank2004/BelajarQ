<?php
include("../database.php");
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit;
}

$email = $_SESSION['email'];


$query = "SELECT username, password, interest FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $query);
$id_query = "SELECT id FROM users WHERE email = '$email'";
$id_result = mysqli_query($conn, $id_query);
$id_row = mysqli_fetch_assoc($id_result);
$id = $id_row['id'];

if ($row = mysqli_fetch_assoc($result)) {
    $currentUsername = $row['username'];
    $currentPassword = $row['password'];
    $currentInterest = $row['interest'];
} else {
    echo "User not found!";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newEmail = mysqli_real_escape_string($conn, $_POST['email']);
    $newUsername = mysqli_real_escape_string($conn, $_POST['username']);
    $newPassword = mysqli_real_escape_string($conn, $_POST['password']);
    $newInterest = mysqli_real_escape_string($conn, $_POST['interest']);

    // Update user's email, username, password, and interest
    $updateQuery = "UPDATE users SET email='$newEmail', username='$newUsername', password='$newPassword', interest='$newInterest' WHERE email='$email'";
    if (mysqli_query($conn, $updateQuery)) {
        $_SESSION['email'] = $newEmail;
        header("Location: profileStudent.php?success=Profile updated successfully");
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

$availableInterests = ['Maths', 'Physics', 'Biology', 'Chemistry'];

?>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="editProfileStudent.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Edit Profile</title>
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
                    <form method="post">
                        <label for="email">New Email:</label><br>
                        <input type="email" name="email" id="email"><br>
                        <label for="username">New Username:</label><br>
                        <input type="text" name="username" id="username"><br>
                        <label for="password">New Password:</label><br>
                        <input type="password" name="password" id="password"><br>
                        <h3>Select Interests:</h3>
                        <?php foreach ($availableInterests as $interest): ?>
                            <input type="radio" name="interest" value="<?php echo $interest; ?>" 
                                <?php echo ($interest == $currentInterest) ? 'checked' : ''; ?>>
                            <label for="interest"><?php echo $interest; ?></label><br>
                        <?php endforeach; 
                        ?>
                        <label for="interest"></label><br>
                        <div class="button-container">
                            <button onclick="window.location.href='profileStudent.php';">Discard Change</button>
                            <input type="submit" name="Edit" value="Save Change">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
