<?php
include("../database.php");
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
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

$interest_query = "SELECT interest FROM users WHERE email = '$email'";
$interest_result = mysqli_query($conn, $interest_query);
$interest_row = mysqli_fetch_assoc($interest_result);
$interest = $interest_row['interest'];

$videos_query= "SELECT id, title, video_file, thumbnail_file FROM course WHERE category = '$interest'";
$videos_result = mysqli_query($conn, $videos_query);

$all_courses_query = "SELECT id, title, video_file, thumbnail_file FROM course ORDER BY RAND()";
$all_courses_result = mysqli_query($conn, $all_courses_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboardStudent.css">
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
            <i class="fa-solid fa-user" onclick="window.location.href='profileStudent.php';"></i>
        </div>
    </div>
    <p>Let's Start Learning, <?php echo htmlspecialchars($username); ?>!</p>
    <div class="find-course">
        <p>Find <span class="bold">Course</span></p>
        <div class="search-container">
            <input type="text" class="search-bar" placeholder="Search for anything">
            <i class="fa-solid fa-magnifying-glass search-icon"></i>
        </div>
    </div>
    <div class="available-course">
        <p>Your <span class="bold">Interest</span></p>
        <!-- <button>View more <span class="bold">Courses</span></button> -->
    </div>
    <?php
        if (mysqli_num_rows($videos_result) > 0) {
            echo "<ul class='video-list'>";
            while ($video = mysqli_fetch_assoc($videos_result)) {
                echo "<li class='video-item'>";
                echo "<a href='courseDetailStudent.php?course_id=" . htmlspecialchars($video['id']) . "'>";
                echo "<img src='" . htmlspecialchars($video['thumbnail_file']) . "' alt='Thumbnail for " . htmlspecialchars($video['title']) . "'>";
                echo "<h4>" . htmlspecialchars($video['title']) . "</h4>";
                echo "</a>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No course available on your interest</p>";
        }
    ?>
    
    <div class="my-course">
        <p>Other <span class="bold">Courses</span></p>
        <!-- <button>All <span class="bold">Courses</span></button> -->
    </div>
    <?php
        if (mysqli_num_rows($all_courses_result) > 0) {
            echo "<ul class='video-list'>";
            while ($course = mysqli_fetch_assoc($all_courses_result)) {
                echo "<li class='video-item'>";
                echo "<a href='courseDetailStudent.php?course_id=" . htmlspecialchars($course['id']) . "'>";
                echo "<img src='" . htmlspecialchars($course['thumbnail_file']) . "' alt='Thumbnail for " . htmlspecialchars($course['title']) . "'>";
                echo "<h4>" . htmlspecialchars($course['title']) . "</h4>";
                echo "</a>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No other courses available</p>";
        }
    ?>
    <div class="footer">
        <h2>Proyek RPL 2024 | Kelompok 11 | BelajarQ</h2>
    </div>
</body>

</html>