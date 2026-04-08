<?php
session_start();
require_once 'config.php';

// Authorization Check: If not logged in, send back to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'header.php';
?>

<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <span class="badge bg-info text-dark">Role: <?php echo $_SESSION['role']; ?></span>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h5>View Courses</h5>
                        <p>Browse available courses in the system.</p>
                        <a href="view_courses.php" class="btn btn-outline-primary">Open</a>
                    </div>
                </div>
            </div>

            <?php if ($_SESSION['role'] == 'Admin'): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 border-primary">
                        <div class="card-body text-center">
                            <h5>Manage Courses</h5>
                            <p>Add, Edit, or Delete course listings.</p>
                            <a href="manage_courses.php" class="btn btn-primary">Manage</a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 border-success">
                        <div class="card-body text-center">
                            <h5>My Registrations</h5>
                            <p>View or drop your registered courses.</p>
                            <a href="my_registrations.php" class="btn btn-success">View My Courses</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h5>Account</h5>
                        <p>Logout of the PCRS system safely.</p>
                        <a href="logout.php" class="btn btn-danger">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>