<?php
include("../database.php");
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['course_id'])) {
    echo "Course ID is not set!";
    exit;
}

$email = $_SESSION['email'];
$course_id = mysqli_real_escape_string($conn, $_GET['course_id']);

$username_query = "SELECT username from users WHERE email = '$email'";
$username_result = mysqli_query($conn, $username_query);
$username_row = mysqli_fetch_assoc($username_result);
$username = $username_row["username"];

$role_query = "SELECT role FROM users WHERE email = '$email'";
$role_result = mysqli_query($conn, $role_query);
$role_row = mysqli_fetch_assoc($role_result);
$role = $role_row['role'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    $user_query = "SELECT id FROM users WHERE email = '$email'";
    $user_result = mysqli_query($conn, $user_query);
    $user_row = mysqli_fetch_assoc($user_result);
    $user_id = $user_row['id'];

    $insert_comment_query = "INSERT INTO comments (username, course_id, comment) VALUES ('$username', '$course_id', '$comment')";
    if (mysqli_query($conn, $insert_comment_query)) {
        echo "Comment added successfully!";
        header("Location: courseDetailStudent.php?course_id=$course_id");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
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
    <form method="POST" action="">
        <label for="comment">Add a comment</label>
        <textarea name="comment" id="comment"></textarea><br><br>
        <input type="submit" name="Add" value="Add Comment">
    </form>
</body>

</html>

