<?php
require_once 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? 0;
$search = isset($_GET['q']) ? "%" . $_GET['q'] . "%" : "%%";

$query = "SELECT c.*, 
          (SELECT COUNT(*) FROM registrations r WHERE r.course_id = c.id AND r.student_id = ?) as is_registered 
          FROM courses c 
          WHERE c.course_name LIKE ? OR c.course_code LIKE ?";

$stmt = $pdo->prepare($query);
$stmt->execute([$user_id, $search, $search]);
$courses = $stmt->fetchAll();

foreach ($courses as $c) {
    echo "<tr>
            <td>" . htmlspecialchars($c['course_code']) . "</td>
            <td>" . htmlspecialchars($c['course_name']) . "</td>
            <td>";
    if ($c['is_registered'] > 0) {
        echo "<button class='btn btn-secondary btn-sm' disabled>Registered</button>";
    } else {
        echo "<form method='POST' style='display:inline;'>
                <input type='hidden' name='course_id' value='{$c['id']}'>
                <button type='submit' name='register_course' class='btn btn-success btn-sm'>Register</button>
              </form>";
    }
    echo "</td></tr>";
}
?>