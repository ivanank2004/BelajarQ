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
$course_query = "SELECT * FROM course WHERE id = '$course_id'";
$course_result = mysqli_query($conn, $course_query);

$uploaded_by_query = "SELECT uploaded_by FROM course WHERE id = $course_id";
$uploaded_by_result = mysqli_query($conn, $uploaded_by_query);
$uploaded_by_row = mysqli_fetch_assoc($uploaded_by_result);
$uploaded_by = $uploaded_by_row['uploaded_by'];

$category_query = "SELECT category FROM course WHERE id = $course_id";
$category_result = mysqli_query($conn, $category_query);
$category_row = mysqli_fetch_assoc($category_result);
$category = $category_row['category'];
if ($course_row = mysqli_fetch_assoc($course_result)) {
    $title = $course_row['title'];
    $description = $course_row['description']; 
    $video_file = $course_row['video_file'];
    $thumbnail_file = $course_row['thumbnail_file'];
} else {
    echo "Course not found!";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Edit'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    if (!empty($_FILES["video"]["name"]) && !empty($_FILES["thumbnail"]["name"])) {
        $video_dir = "../uploads/videos/";
        $video_file = $video_dir . basename($_FILES["video"]["name"]);
        $videoFileType = strtolower(pathinfo($video_file, PATHINFO_EXTENSION));

        $thumbnail_dir = "../uploads/thumbnails/";
        $thumbnail_file = $thumbnail_dir . basename($_FILES["thumbnail"]["name"]);
        $thumbnailFileType = strtolower(pathinfo($thumbnail_file, PATHINFO_EXTENSION));

        if (!is_dir($video_dir)) mkdir($video_dir, 0777, true);
        if (!is_dir($thumbnail_dir)) mkdir($thumbnail_dir, 0777, true);

        if (move_uploaded_file($_FILES["video"]["tmp_name"], $video_file) && move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $thumbnail_file)) {
            $video_file = mysqli_real_escape_string($conn, $video_file);
            $thumbnail_file = mysqli_real_escape_string($conn, $thumbnail_file);
        } else {
            echo "Sorry, there was an error uploading your files.";
        }
    }
    $updateQuery = "UPDATE course SET ";
    $updates = [];
    if (!empty($title)) $updates[] = "title = '$title'";
    if (!empty($description)) $updates[] = "description = '$description'";
    if (!empty($category)) $updates[] = "category = '$category'";
    if (!empty($content)) $updates[] = "content = '$content'";
    if (isset($video_file)) $updates[] = "video_file = '$video_file'";
    if (isset($thumbnail_file)) $updates[] = "thumbnail_file = '$thumbnail_file'";
    
    if (!empty($updates)) {
        $updateQuery .= implode(", ", $updates);
        $updateQuery .= " WHERE id = '$course_id'";

        if (mysqli_query($conn, $updateQuery)) {
            echo "Course updated successfully.";
            header("Location: courseDetail.php?course_id=$course_id");
            exit;
        } else {
            echo "Error updating course: " . mysqli_error($conn);
        }
    } else {
        echo "No changes made.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
</head>
<body>
    <h1>Edit Course</h1>
    <form action="editCourse.php?course_id=<?php echo htmlspecialchars($course_id); ?>" method="post" enctype="multipart/form-data">
        <label for="title">Video Title:</label>
        <input type="text" name="title" id="title"><br><br>
        <label for="description">Description:</label>
        <textarea name="description" id="description"></textarea><br><br>
        <label for="content">Content:</label>
        <textarea name="content" id="content"></textarea><br><br>
        <label for="category">Category:</label>
        <select name="category" id="category">
            <option value="Maths">Maths</option>
            <option value="Physics">Physics</option>
            <option value="Biology">Biology</option>
            <option value="Chemistry">Chemistry</option>
        </select><br><br>
        <label for="video">Choose Video:</label>
        <input type="file" name="video" id="video" accept="video/*"><br><br>
        <label for="thumbnail">Thumbnail:</label>
        <input type="file" name="thumbnail" id="thumbnail" accept="image/*"><br><br>

        <input type="submit" name="Edit" value="Save Changes">
        <button type="button" onclick="window.location.href='courseDetailTeacher.php?course_id=<?php echo $course_id; ?>';">Back</button>
    </form>
</body>
</html>