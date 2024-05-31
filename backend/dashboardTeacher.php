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

$videos_query = "SELECT id, title, video_file, thumbnail_file FROM course WHERE uploaded_by = '$username'";
$videos_result = mysqli_query($conn, $videos_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboardTeacher.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Dashboard</title>
</head>

<body>
    <div class="navbar">
        <div class="image">
            <img src="../BelajarQ.png" alt="logo">
        </div>
        <div class="profile-button">
            <i class="fa-solid fa-user" onclick="window.location.href='profileTeacher.php';"></i>
        </div>
    </div>
    <p>What are you teaching today, <?php echo htmlspecialchars($username); ?>?</p>
    <div class="create-course">
        <button onclick="window.location.href='createCourse.php';">Create New <span class="bold">Course</span></button>
    </div>
    <div class="your-course">
        <p>Your Course</p>
        <button>View more <span class="bold">Courses</span></button>
    </div>
    <?php
    if (mysqli_num_rows($videos_result) > 0) {
        echo "<ul class='video-list'>";
        while ($video = mysqli_fetch_assoc($videos_result)) {
            echo "<li class='video-item'>";
            echo "<a href='courseDetailTeacher.php?course_id=" . htmlspecialchars($video['id']) . "'>";
            echo "<img src='" . htmlspecialchars($video['thumbnail_file']) . "' alt='Thumbnail for " . htmlspecialchars($video['title']) . "'>";
            echo "<h4>" . htmlspecialchars($video['title']) . "</h4>";
            echo "</a>";
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>You have not uploaded any videos yet.</p>";
    }
    ?>
    <div class="footer">
        <h2>Proyek RPL 2024 | Kelompok 11 | BelajarQ</h2>
    </div>
</body>

</html>