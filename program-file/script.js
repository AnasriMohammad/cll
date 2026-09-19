// Mobile menu toggle
document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("menuToggle");
    const navMenu = document.getElementById("navMenu");

    if (menuToggle && navMenu) {
        menuToggle.addEventListener("click", function () {
            navMenu.classList.toggle("active");
        });
    }
});

// Student Form Validation
function validateStudentForm() {
    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const course = document.getElementById("course_id").value;
    const phone = document.getElementById("phone").value.trim();

    if (!name || !email || !course || !phone) {
        alert("Kripya sabhi zaroori fields bharein!");
        return false;
    }

    if (phone.length < 10) {
        alert("Mobile number kam se kam 10 digits ka hona chahiye.");
        return false;
    }

    return true;
}