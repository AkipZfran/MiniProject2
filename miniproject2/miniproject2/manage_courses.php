<?php
session_start();
require_once 'config.php';

// Only Admins allowed
if ($_SESSION['role'] !== 'Admin') {
    header("Location: dashboard.php");
    exit();
}

// Handle Adding a Course
// Handle Adding a Course
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_course'])) {
    $code = $_POST['course_code'];
    $name = $_POST['course_name'];
    $cap = $_POST['capacity'];

    try {
        $stmt = $pdo->prepare("INSERT INTO courses (course_code, course_name, capacity) VALUES (?, ?, ?)");
        $stmt->execute([$code, $name, $cap]);
        $msg = "Course added successfully!";
    } catch (PDOException $e) {
        // Check if the error code is 23000 (Duplicate Entry)
        if ($e->getCode() == 23000) {
            $error = "Error: Course code '$code' already exists!";
        } else {
            $error = "Database Error: " . $e->getMessage();
        }
    }
}

// Handle Deleting a Course
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage_courses.php");
}

$courses = $pdo->query("SELECT * FROM courses")->fetchAll();
include 'header.php';
?>

<h3>Manage Courses</h3>
<?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>

<div class="card mb-4">
    <div class="card-body">
        <form method="POST" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="course_code" class="form-control" placeholder="Course Code (e.g. DFP40443)" required>
            </div>
            <div class="col-md-5">
                <input type="text" name="course_name" class="form-control" placeholder="Course Name" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="capacity" class="form-control" placeholder="Capacity" required>
            </div>
            <div class="col-md-2">
                <button type="submit" name="add_course" class="btn btn-success w-100">Add Course</button>
            </div>
        </form>
    </div>
</div>

<div class="mt-3">
    <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
</div>

<table class="table table-bordered bg-white">
    <thead class="table-dark">
        <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Capacity</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($courses as $c): ?>
        <tr>
            <td><?php echo htmlspecialchars($c['course_code']); ?></td>
            <td><?php echo htmlspecialchars($c['course_name']); ?></td>
            <td><?php echo $c['capacity']; ?></td>
           <td>
                <a href="edit_course.php?id=<?php echo $c['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="manage_courses.php?delete=<?php echo $c['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this course?')">Delete</a>
        </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</div>

<?php include 'footer.php'; ?>