<?php
session_start();
require_once 'config.php';

// Authorization: Admin only
if ($_SESSION['role'] !== 'Admin') {
    header("Location: dashboard.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$id]);
$course = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST['course_code'];
    $name = $_POST['course_name'];
    $cap = $_POST['capacity'];

    $updateStmt = $pdo->prepare("UPDATE courses SET course_code = ?, course_name = ?, capacity = ? WHERE id = ?");
    $updateStmt->execute([$code, $name, $cap, $id]);
    header("Location: manage_courses.php?msg=updated");
    exit();
}

include 'header.php';
?>

<h3>Edit Course</h3>
<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label>Course Code</label>
                <input type="text" name="course_code" class="form-control" value="<?php echo htmlspecialchars($course['course_code']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Course Name</label>
                <input type="text" name="course_name" class="form-control" value="<?php echo htmlspecialchars($course['course_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Capacity</label>
                <input type="number" name="capacity" class="form-control" value="<?php echo $course['capacity']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Course</button>
            <a href="manage_courses.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>