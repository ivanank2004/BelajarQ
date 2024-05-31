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
$isStudent = ($role === 'student');

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

$comments_query = "SELECT username, comment FROM comments WHERE course_id = '$course_id'";
$comments_result = mysqli_query($conn, $comments_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="courseDetailStudent.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Course Details</title>
    
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
        <div class="wrapper">
            <div class="details-container">
                <div class="title">
                    <h1><?php echo htmlspecialchars($course_row['title']); ?></h1>
                </div>

                <p id="created">Created By: <?php echo htmlspecialchars($course_row['uploaded_by']); ?></p>
                <p id="category">Category: <?php echo htmlspecialchars($course_row['category']); ?></p>
                <p id="description">Course Detail</p>
                <p id="course-description"><?php echo htmlspecialchars($course_row['description']); ?></p>
                <p id="content">Content</p>
                <p id="course-content"><?php echo nl2br(htmlspecialchars($course_row['content'])); ?></p>
            </div>
            <div class="video-container">
                <div class="video-player">
                    <video controls style="width: 100%;">
                        <source src="<?php echo htmlspecialchars($course_row['video_file']); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
            <div class="button-container">
                <button id="dashboard" onclick="window.location.href='dashboardStudent.php';">Back to Dashboard</button>
            </div>
            <h2>Comments</h2>
            <div class="comment-form">
                <form method="POST" action="">
                    <textarea name="comment" id="comment"></textarea><br><br>
                    <input type="submit" name="Add" value="Add Comment">
                </form>
            </div>
            <div class="comments-section">
                <?php while ($comment_row = mysqli_fetch_assoc($comments_result)): ?>
                    <p><strong><?php echo htmlspecialchars($comment_row['username']); ?></strong></p>
                    <p><?php echo htmlspecialchars($comment_row['comment']); ?></p>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</body>

</html>