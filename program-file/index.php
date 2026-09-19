<?php
require_once 'db.php';

// Student Delete
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit;
}

// SQL 2-Table INNER JOIN: Student + Course + Faculty
$query = "SELECT 
            s.id, 
            s.name, 
            s.email, 
            s.phone, 
            c.course_name, 
            c.course_code, 
            f.faculty_name 
          FROM students s
          INNER JOIN courses c ON s.course_id = c.id
          INNER JOIN faculties f ON s.faculty_id = f.id
          ORDER BY s.id DESC";

$result = $conn->query($query);

include 'header.php';
?>

<div class="card">
    <div class="page-title-bar">
        <div>
            <h2><i class="fa-solid fa-users"></i> Enrolled Students Directory</h2>
            <p class="subtitle">Connected to Courses & Faculties tables through Relational SQL JOINs</p>
        </div>
        <a href="add_student.php" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i> + Add New Student</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Roll ID</th>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Enrolled Course</th>
                    <th>Parent Faculty</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><span class="badge-id">#<?php echo $row['id']; ?></span></td>
                            <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><span class="badge-course"><?php echo htmlspecialchars($row['course_name']); ?></span></td>
                            <td><span class="badge-faculty"><?php echo htmlspecialchars($row['faculty_name']); ?></span></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td>
                                <a href="index.php?delete_id=<?php echo $row['id']; ?>" 
                                   class="btn-delete" 
                                   onclick="return confirm('Kya aap is student record ko delete karna chahte hain?');">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="empty-state">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <p>Abhi koi student register nahi hai. Naya student add karne ke liye upar button dabayein.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>