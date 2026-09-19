<?php
require_once 'db.php';

$msg = "";
$msg_type = "";

// Course Add Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_course'])) {
    $course_name = trim($_POST['course_name']);
    $course_code = trim($_POST['course_code']);
    $duration    = trim($_POST['duration']);
    $faculty_id  = intval($_POST['faculty_id']);

    if (!empty($course_name) && !empty($course_code) && !empty($duration) && $faculty_id > 0) {
        $stmt = $conn->prepare("INSERT INTO courses (course_name, course_code, duration, faculty_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $course_name, $course_code, $duration, $faculty_id);
        if ($stmt->execute()) {
            $msg = "Naya course safalta-poorvak add ho gaya!";
            $msg_type = "success";
        } else {
            $msg = "Error: Course code pehle se register ho sakta hai.";
            $msg_type = "danger";
        }
        $stmt->close();
    }
}

// Course Delete Logic
if (isset($_GET['del_course'])) {
    $cid = intval($_GET['del_course']);
    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->bind_param("i", $cid);
    $stmt->execute();
    $stmt->close();
    header("Location: courses.php");
    exit;
}

// Fetch faculties for dropdown
$faculty_list = $conn->query("SELECT id, faculty_name FROM faculties ORDER BY faculty_name ASC");

// JOIN Query: Courses JOIN Faculties
$query = "SELECT c.*, f.faculty_name, COUNT(s.id) as enrolled_students 
          FROM courses c 
          INNER JOIN faculties f ON c.faculty_id = f.id 
          LEFT JOIN students s ON c.id = s.course_id 
          GROUP BY c.id 
          ORDER BY c.id DESC";
$courses = $conn->query($query);

include 'header.php';
?>

<div class="split-layout">
    <!-- Form Card -->
    <div class="card form-box">
        <div class="page-title-bar">
            <h2><i class="fa-solid fa-plus-circle"></i> Add New Course</h2>
        </div>

        <?php if ($msg): ?>
            <div class="alert-<?php echo $msg_type; ?>"><?php echo $msg; ?></div>
        <?php endif; ?>

        <form action="courses.php" method="POST">
            <div class="form-group">
                <label>Select Parent Faculty (JOIN)</label>
                <select name="faculty_id" required>
                    <option value="">-- Choose Faculty --</option>
                    <?php if ($faculty_list && $faculty_list->num_rows > 0): ?>
                        <?php while ($f = $faculty_list->fetch_assoc()): ?>
                            <option value="<?php echo $f['id']; ?>"><?php echo htmlspecialchars($f['faculty_name']); ?></option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Course Name</label>
                <input type="text" name="course_name" placeholder="e.g. B.Tech Computer Science" required>
            </div>
            <div class="form-group">
                <label>Course Code</label>
                <input type="text" name="course_code" placeholder="e.g. CS-2026" required>
            </div>
            <div class="form-group">
                <label>Duration</label>
                <input type="text" name="duration" placeholder="e.g. 4 Years / 3 Years" required>
            </div>
            <button type="submit" name="add_course" class="btn btn-primary" style="width:100%; justify-content:center;">
                <i class="fa-solid fa-save"></i> Save Course
            </button>
        </form>
    </div>

    <!-- Table Card -->
    <div class="card table-box">
        <div class="page-title-bar">
            <div>
                <h2><i class="fa-solid fa-book-open"></i> Course Directory</h2>
                <p class="subtitle">Courses and their linked Faculties (via SQL JOIN)</p>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Course Title</th>
                        <th>Linked Faculty</th>
                        <th>Duration</th>
                        <th>Students</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($courses && $courses->num_rows > 0): ?>
                        <?php while ($c = $courses->fetch_assoc()): ?>
                            <tr>
                                <td><span class="badge-id"><?php echo htmlspecialchars($c['course_code']); ?></span></td>
                                <td><strong><?php echo htmlspecialchars($c['course_name']); ?></strong></td>
                                <td><span class="badge-faculty"><?php echo htmlspecialchars($c['faculty_name']); ?></span></td>
                                <td><?php echo htmlspecialchars($c['duration']); ?></td>
                                <td><span class="badge-count"><?php echo $c['enrolled_students']; ?> Enrolled</span></td>
                                <td>
                                    <a href="courses.php?del_course=<?php echo $c['id']; ?>" 
                                       class="btn-delete"
                                       onclick="return confirm('Kya aap is course ko delete karna chahte hain?');">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="fa-solid fa-folder-open"></i>
                                <p>Koi courses nahi mile. Left side se create karein.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>