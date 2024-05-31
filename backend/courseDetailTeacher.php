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
$role_query = "SELECT role FROM users WHERE email = '$email'";
$role_result = mysqli_query($conn, $role_query);
$role_row = mysqli_fetch_assoc($role_result);
$role = $role_row['role'];

$course_id = mysqli_real_escape_string($conn, $_GET['course_id']);
$course_query = "SELECT * FROM course WHERE id = '$course_id'";
$course_result = mysqli_query($conn, $course_query);

if (!$course_row = mysqli_fetch_assoc($course_result)) {
    echo "Course not found!";
    exit;
}
$username_query = "SELECT username FROM users WHERE email = '$email'";
$username_result = mysqli_query($conn, $username_query);
$username_row = mysqli_fetch_assoc($username_result);
$username = $username_row['username'];

$isCurrentUserUploader = ($course_row['uploaded_by'] === $username);

$category_query = "SELECT category FROM course WHERE id = $course_id";
$category_result = mysqli_query($conn, $category_query);
$category_row = mysqli_fetch_assoc($category_result);
$category = $category_row['category'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Delete'])) {
    $deleteQuery = "DELETE FROM course WHERE id = '$course_id'";
    if (mysqli_query($conn, $deleteQuery)) {
        echo "Course deleted successfully.";
        header("Location: dashboardTeacher.php");
        exit;
    } else {
        echo "Error deleting course: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 20px;
        }
        .container {
            display: flex;
            width: 100%;
            max-width: 1200px;
        }
        .video-container {
            flex: 1;
            margin-left: 20px;
        }
        .video-player {
            width: 100%;
            height: auto;
            background-color: #f2f2f2;
        }
        .details-container {
            flex: 1;
        }
        h1, h2, p {
            margin: 0 0 10px 0;
        }
        button {
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="details-container">
            <h1><?php echo htmlspecialchars($course_row['title']); ?></h1>
            <h2>Created By: <?php echo htmlspecialchars($course_row['uploaded_by']); ?></h2>
            <h2>Category: <?php echo htmlspecialchars($course_row['category']); ?></h2>
            <p><?php echo htmlspecialchars($course_row['description']); ?></p>
            <p><?php echo nl2br(htmlspecialchars($course_row['content'])); ?></p>
            <button onclick="window.location.href='dashboardTeacher.php';">Back to Dashboard</button>
            <?php if ($isCurrentUserUploader): ?>
            <button onclick="window.location.href='editCourse.php?course_id=<?php echo htmlspecialchars($course_id); ?>';">Edit</button>
            <form method="post">
                <button type="submit" name="Delete" onclick="return confirm('Are you sure you want to delete this course?')">Delete</button>
            </form>
            <?php endif; ?>
        </div>
        <div class="video-container">
            <div class="video-player">
                <video controls style="width: 100%;">
                    <source src="<?php echo htmlspecialchars($course_row['video_file']); ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    </div>
</body>
</html>
