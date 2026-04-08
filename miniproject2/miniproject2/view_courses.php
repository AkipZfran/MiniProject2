<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle Registration Logic
if (isset($_POST['register_course'])) {
    $course_id = $_POST['course_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO registrations (student_id, course_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $course_id]);
        $success = "Successfully registered for the course!";
    } catch (PDOException $e) {
        // Handle duplicate registration error
        $error = "You are already registered for this course.";
    }
}

// Get all courses and check if the current student is already registered
$query = "SELECT c.*, 
          (SELECT COUNT(*) FROM registrations r WHERE r.course_id = c.id AND r.student_id = ?) as is_registered 
          FROM courses c";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);
$courses = $stmt->fetchAll();

include 'header.php';
?>

<h3>Available Courses</h3>
<?php if(isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
<?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

<div class="mb-3">
    <input type="text" id="courseSearch" class="form-control" placeholder="Search by course name or code...">
</div>

<div class="table-responsive">
    <table class="table table-hover bg-white shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Code</th>
                <th>Course Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($courses as $c): ?>
            <tr>
                <td><?php echo htmlspecialchars($c['course_code']); ?></td>
                <td><?php echo htmlspecialchars($c['course_name']); ?></td>
                <td>
                    <?php if ($c['is_registered'] > 0): ?>
                        <button class="btn btn-secondary btn-sm" disabled>Registered</button>
                    <?php else: ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="course_id" value="<?php echo $c['id']; ?>">
                            <button type="submit" name="register_course" class="btn btn-success btn-sm">Register</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="mt-3">
    <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
</div>

<script>
document.getElementById('courseSearch').addEventListener('keyup', function() {
    let query = this.value;
    let xhr = new XMLHttpRequest();
    xhr.open('GET', 'search_courses.php?q=' + query, true);
    xhr.onload = function() {
        if (this.status == 200) {
            document.querySelector('tbody').innerHTML = this.responseText;
        }
    };
    xhr.send();
});
</script>

<?php include 'footer.php'; ?>