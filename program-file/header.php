<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Portal - College Management</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="script.js" defer></script>
</head>
<body>

    <!-- Top Contact Bar -->
    <div class="top-bar">
        <div class="top-bar-content">
            <span><i class="fa-solid fa-phone"></i> +91 98765 43210</span>
            <span><i class="fa-solid fa-envelope"></i> helpdesk@campusportal.edu</span>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="main-header">
        <div class="nav-container">
            <a href="index.php" class="brand-logo">
                <i class="fa-solid fa-graduation-cap"></i>
                <span>Campus<span class="highlight">Portal</span></span>
            </a>

            <button class="mobile-toggle" id="menuToggle" aria-label="Toggle Navigation">
                <i class="fa-solid fa-bars"></i>
            </button>

            <nav class="nav-menu" id="navMenu">
                <a href="index.php" class="nav-link"><i class="fa-solid fa-user-graduate"></i> Students</a>
                <a href="faculties.php" class="nav-link"><i class="fa-solid fa-building-columns"></i> Faculties</a>
                <a href="courses.php" class="nav-link"><i class="fa-solid fa-book-open"></i> Courses</a>
                <a href="add_student.php" class="nav-btn"><i class="fa-solid fa-plus"></i> Add Student</a>
            </nav>
        </div>
    </header>

    <main class="main-body">