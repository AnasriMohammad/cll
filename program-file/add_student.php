<?php
require_once 'db.php';

$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name       = trim($_POST['name']);
    $email      = trim($_POST['email']);
    $phone      = trim($_POST['phone']);
    $course_id  = intval($_POST['course_id']);

    if (!empty($name) && !empty($email) && !empty($phone) && $course_id > 0) {
        // Course ID se iski linked Faculty ID nikalna
        $c_stmt = $conn->prepare("SELECT faculty_id FROM courses WHERE id = ?");
        $c_stmt->bind_param("i", $course_id);
        $c_stmt->execute();
        $c_res = $c_stmt->get_result();
        
        if ($c_row = $c_res->fetch_assoc()) {
            $faculty_id = $c_row['faculty_id'];

            // Student save karna
            $stmt = $conn->prepare("INSERT INTO students (name, email, phone, course_id, faculty_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssii", $name, $email, $phone, $course_id, $faculty_id);

            if ($stmt->execute()) {
                header("Location: index.php");
                exit;
            } else {
                $error_msg = "Error: Ye student email ID pehle se registered hai.";
            }
            $stmt->close();
        } else {
            $error_msg = "Chuna gaya course valid nahi hai.";
        }
        $c_stmt->close();
    } else {
        $error_msg = "Kripya sabhi fields dhyan se bharein.";
    }
}

// Fetch Courses with Faculty Name using JOIN for selection
$course_dropdown = $conn->query("
    SELECT c.id, c.course_name, c.course_code, f.faculty_name 
    FROM courses c 
    INNER JOIN faculties f ON c.faculty_id = f.id 
    ORDER BY c.course_name ASC
");

include 'header.php';
?>

<div class="card form-card">
    <div class="page-title-bar">
        <h2><i class="fa-solid fa-user-graduate"></i> Register New Student</h2>
        <a href="index.php" class="btn-outline"><i class="fa-solid fa-arrow-left"></i> View Directory</a>
    </div>

    <?php if ($error_msg): ?>
        <div class="alert-danger"><?php echo $error_msg; ?></div>
    <?php endif; ?>

    <form action="add_student.php" method="POST" onsubmit="return validateStudentForm()">
        <div class="form-grid">
            <div class="form-group">
                <label><i class="fa-solid fa-user"></i> Full Name</label>
                <input type="text" id="name" name="name" placeholder="e.g. Rahul Sharma" required>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-envelope"></i> Email Address</label>
                <input type="email" id="email" name="email" placeholder="e.g. rahul@example.com" required>
            </div>

            <div class="form-group full-width">
                <label><i class="fa-solid fa-book-open"></i> Select Course (Linked with Faculty)</label>
                <select id="course_id" name="course_id" required>
                    <option value="">-- Choose Course --</option>
                    <?php if ($course_dropdown && $course_dropdown->num_rows > 0): ?>
                        <?php while ($c = $course_dropdown->fetch_assoc()): ?>
                            <option value="<?php echo $c['id']; ?>">
                                <?php echo htmlspecialchars($c['course_name']); ?> (<?php echo htmlspecialchars($c['course_code']); ?>) — Department: <?php echo htmlspecialchars($c['faculty_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="">Pehle Course add karein!</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group full-width">
                <label><i class="fa-solid fa-phone"></i> Mobile Phone Number</label>
                <input type="text" id="phone" name="phone" placeholder="e.g. 9876543210" required>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Register Student</button>
            <a href="index.php" class="btn-cancel">Cancel</a>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>