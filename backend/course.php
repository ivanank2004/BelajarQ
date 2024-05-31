<?php
include("database.php");
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['course_id'])) {
    echo "Course ID is not set!";
    exit;
}
$email = $_SESSION['email'];
$role_query = "SELECT role FROM users WHERE email = '$email'";
$role_result = mysqli_query($conn, $role_query);
$role_row = mysqli_fetch_assoc($role_result);
$role = $role_row['role'];
$dashboardURL = ($role === 'teacher') ? 'Teacher/dashboardTeacher.php' : 'Student/dashboardStudent.php';

$course_id = mysqli_real_escape_string($conn, $_GET['course_id']);
$course_query = "SELECT * FROM course WHERE id = '$course_id'";
$course_result = mysqli_query($conn, $course_query);

$content_query = "SELECT content FROM course WHERE id = $course_id";
$content_result = mysqli_query($conn, $content_query);
$content_row = mysqli_fetch_assoc($content_result);
$content = $content_row['content'];

if ($course_row = mysqli_fetch_assoc($course_result)) {
    $title = $course_row['title'];
    $description = $course_row['description']; 
    $video_file = $course_row['video_file'];
    $thumbnail_file = $course_row['thumbnail_file'];
} else {
    echo "Course not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Details</title>
</head>
<body>
    <h1><?php echo htmlspecialchars($title); ?></h1>
    <p><?php echo htmlspecialchars($description); ?></p>
    <p><?php echo nl2br(htmlspecialchars($content)); ?></p>
    <button onclick="window.location.href='<?php echo $dashboardURL; ?>';">Back to Dashboard</button>
</body>
</html>