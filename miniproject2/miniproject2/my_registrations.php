<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle Drop Course
if (isset($_GET['drop'])) {
    $reg_id = $_GET['drop'];
    $stmt = $pdo->prepare("DELETE FROM registrations WHERE id = ? AND student_id = ?");
    $stmt->execute([$reg_id, $user_id]);
    header("Location: my_registrations.php");
}

$stmt = $pdo->prepare("SELECT r.id as reg_id, c.course_code, c.course_name 
                       FROM registrations r 
                       JOIN courses c ON r.course_id = c.id 
                       WHERE r.student_id = ?");
$stmt->execute([$user_id]);
$my_courses = $stmt->fetchAll();

include 'header.php';
?>

<h3>My Registered Courses</h3>

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>Course Code</th>
            <th>Course Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($my_courses) > 0): ?>
            <?php foreach ($my_courses as $mc): ?>
            <tr>
                <td><?php echo htmlspecialchars($mc['course_code']); ?></td>
                <td><?php echo htmlspecialchars($mc['course_name']); ?></td>
                <td>
                    <a href="my_registrations.php?drop=<?php echo $mc['reg_id']; ?>" 
                       class="btn btn-danger btn-sm" 
                       onclick="return confirm('Are you sure you want to drop this course?')">Drop</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3" class="text-center">No courses registered yet.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<div class="mt-3">
    <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
</div>

<?php include 'footer.php'; ?>