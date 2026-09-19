<?php
require_once 'db.php';

$msg = "";
$msg_type = "";

// Faculty Add Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_faculty'])) {
    $faculty_name = trim($_POST['faculty_name']);
    $dean_name    = trim($_POST['dean_name']);
    $email        = trim($_POST['email']);

    if (!empty($faculty_name) && !empty($dean_name) && !empty($email)) {
        $stmt = $conn->prepare("INSERT INTO faculties (faculty_name, dean_name, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $faculty_name, $dean_name, $email);
        if ($stmt->execute()) {
            $msg = "Faculty record successfully joda gaya!";
            $msg_type = "success";
        } else {
            $msg = "Error: Ye faculty name ya email pehle se maujood hai.";
            $msg_type = "danger";
        }
        $stmt->close();
    }
}

// Faculty Delete Logic
if (isset($_GET['del_faculty'])) {
    $fid = intval($_GET['del_faculty']);
    $stmt = $conn->prepare("DELETE FROM faculties WHERE id = ?");
    $stmt->bind_param("i", $fid);
    $stmt->execute();
    $stmt->close();
    header("Location: faculties.php");
    exit;
}

// Fetch all faculties with total courses count using JOIN
$query = "SELECT f.*, COUNT(c.id) as total_courses 
          FROM faculties f 
          LEFT JOIN courses c ON f.id = c.faculty_id 
          GROUP BY f.id 
          ORDER BY f.id DESC";
$faculties = $conn->query($query);

include 'header.php';
?>

<div class="split-layout">
    <!-- Form Card -->
    <div class="card form-box">
        <div class="page-title-bar">
            <h2><i class="fa-solid fa-plus-circle"></i> Add Faculty</h2>
        </div>

        <?php if ($msg): ?>
            <div class="alert-<?php echo $msg_type; ?>"><?php echo $msg; ?></div>
        <?php endif; ?>

        <form action="faculties.php" method="POST">
            <div class="form-group">
                <label>Faculty / Department Name</label>
                <input type="text" name="faculty_name" placeholder="e.g. Faculty of Life Sciences" required>
            </div>
            <div class="form-group">
                <label>Dean / Head of Department</label>
                <input type="text" name="dean_name" placeholder="e.g. Dr. R. K. Gupta" required>
            </div>
            <div class="form-group">
                <label>Official Email</label>
                <input type="email" name="email" placeholder="e.g. dean.science@campus.edu" required>
            </div>
            <button type="submit" name="add_faculty" class="btn btn-primary" style="width:100%; justify-content:center;">
                <i class="fa-solid fa-save"></i> Save Faculty
            </button>
        </form>
    </div>

    <!-- Table Card -->
    <div class="card table-box">
        <div class="page-title-bar">
            <div>
                <h2><i class="fa-solid fa-building-columns"></i> Faculty List</h2>
                <p class="subtitle">College ke sabhi departments aur unke Deans</p>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Faculty Department</th>
                        <th>Dean / Head</th>
                        <th>Email</th>
                        <th>Courses</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($faculties && $faculties->num_rows > 0): ?>
                        <?php while ($f = $faculties->fetch_assoc()): ?>
                            <tr>
                                <td><span class="badge-id">#<?php echo $f['id']; ?></span></td>
                                <td><strong><?php echo htmlspecialchars($f['faculty_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($f['dean_name']); ?></td>
                                <td><?php echo htmlspecialchars($f['email']); ?></td>
                                <td><span class="badge-count"><?php echo $f['total_courses']; ?> Courses</span></td>
                                <td>
                                    <a href="faculties.php?del_faculty=<?php echo $f['id']; ?>" 
                                       class="btn-delete"
                                       onclick="return confirm('Dhyan dein: Is faculty ko delete karne se iske saare courses aur students bhi delete ho jayenge!');">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="fa-solid fa-folder-open"></i>
                                <p>Koi faculty nahi mili. Left side form se create karein.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>