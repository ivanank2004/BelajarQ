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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Create'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $uploaded_by = $username;
    $video_dir = "../uploads/videos/";
    $video_file = $video_dir . basename($_FILES["video"]["name"]);
    $videoFileType = strtolower(pathinfo($video_file, PATHINFO_EXTENSION));

    $thumbnail_dir = "../uploads/thumbnails/";
    $thumbnail_file = $thumbnail_dir . basename($_FILES["thumbnail"]["name"]);
    $thumbnailFileType = strtolower(pathinfo($thumbnail_file, PATHINFO_EXTENSION));

    if (!is_dir($video_dir)) mkdir($video_dir, 0777, true);
    if (!is_dir($thumbnail_dir)) mkdir($thumbnail_dir, 0777, true);

    if (move_uploaded_file($_FILES["video"]["tmp_name"], $video_file) && move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $thumbnail_file)) {
        $sql = "INSERT INTO course (title, description, video_file, category, uploaded_by, thumbnail_file, content) VALUES ('$title', '$description', '$video_file', '$category', '$uploaded_by', '$thumbnail_file', '$content')";
        if (mysqli_query($conn, $sql)) {
            echo "The video and thumbnail have been uploaded.";
            header("Location: dashboardTeacher.php");
            exit;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Sorry, there was an error uploading your files.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="createCourse.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Create Course</title>
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
    <div class="container">
        <div class="wrapper">
            <h1>Create Course</h1>
            <div class="form-container">
                <form action="createCourse.php" method="post" enctype="multipart/form-data">
                    <label for="title">Name your Course<Br></label>
                    <input type="text" name="title" id="title" required><br><br>
                    <div class="course-category">
                        <label for="category">What are you Teaching?<br></label>
                        <select name="category" id="category" required>
                            <option value="Maths">Math</option>
                            <option value="Physics">Physics</option>
                            <option value="Biology">Biology</option>
                            <option value="Chemistry">Chemistry</option>
                        </select><br><br>
                    </div>
                    <label for="description">What is this Course about?<br></label>
                    <textarea name="description" id="description" required></textarea><br><br>
                    <label for="content">Content</label><br>
                    <textarea name="content" id="content"></textarea><br>
                    <div class="upload-container">
                        <label for="video">Video:</label>
                        <input type="file" name="video" id="video" accept="video/*" required><br><br>
                        <label for="thumbnail">Thumbnail:</label>
                        <input type="file" name="thumbnail" id="thumbnail" accept="image/*" required><br><br>
                    </div>
                    <div class="button-container">
                        <button onclick="window.location.href='dashboardTeacher.php';">Cancel</button>
                        <input type="submit" name="Create" value="Start your Course">
                    </div>
            </div>
        </div>
    </div>
    </form>
</body>

</html>
