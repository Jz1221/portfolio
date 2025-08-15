<?php
// This ensure session is started at the very beginning to handle messages
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include your database connection file
include 'connection.php';

// --- Handle Contact Form Submission (Saves to Database) ---
// This block processes the contact form data when it's submitted via POST request.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_contact_message'])) {
    // Collect and sanitize form data to prevent XSS attacks
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Basic validation: Check if fields are empty and email format is valid
    if (empty($name) || empty($email) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['form_message'] = 'Please fill in all fields correctly.';
        $_SESSION['form_status'] = 'error';
    } else {
        // Prepare data for database insertion, escaping special characters to prevent SQL injection
        $name_db = $conn->real_escape_string($name);
        $email_db = $conn->real_escape_string($email);
        $message_db = $conn->real_escape_string($message);

        // SQL INSERT query to save the message into the 'contact_messages' table
        $sql = "INSERT INTO contact_messages (name, email, message) VALUES ('$name_db', '$email_db', '$message_db')";

        // Execute the query and set session message based on success or failure
        if ($conn->query($sql) === TRUE) {
            $_SESSION['form_message'] = 'Your message has been saved successfully!';
            $_SESSION['form_status'] = 'success';
        } else {
            // Include database error for debugging purposes
            $_SESSION['form_message'] = 'There was an error saving your message: ' . $conn->error;
            $_SESSION['form_status'] = 'error';
        }
    }
    // Redirect back to the contact section of the same page to prevent form re-submission
    // on refresh and to display the session message.
    header("Location: sjz.php#contact");
    exit(); // Always exit after a header redirect
}

// --- Fetch all data for display from the database ---
// Data is fetched regardless of whether a form was submitted, to ensure the page always displays current info.
$projects       = $conn->query("SELECT * FROM projects ORDER BY id DESC"); // Fetch all projects
$skills         = $conn->query("SELECT * FROM skills ORDER BY id DESC"); // Fetch all skills directly
$experience     = $conn->query("SELECT * FROM experience ORDER BY id DESC"); // Fetch all experience
$education      = $conn->query("SELECT * FROM education ORDER BY id DESC"); // Fetch all education
$certifications = $conn->query("SELECT * FROM certifications ORDER BY id DESC"); // Fetch all certifications
?>

<!--HTML-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Song Jia Zheng - Portfolio</title>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.0/dist/boxicons.js">
    <link rel="stylesheet" href="sjz.css">
</head>
<body>

<header>
    <h1>Song Jia Zheng</h1>
    <p>Final Year Student in Segi College Subang Jaya  </p>
</header>

<nav>
    <a href="#home">Home</a>
    <a href="#about">About Me</a>
    <a href="#skills">My Skills</a>
    <a href="#projects">My Projects</a>
    <a href="#experience">Work Experience</a>
    <a href="#education">My Education</a>
    <a href="#certifications">My Certifications</a>
    <a href="#contact">Contact Me</a>
</nav>

<!--Home Section-->
<section id="home" class="section-animated">
    <div class="home-content">
        <div class="home-text">
            <h2>Hello, I'm Song Jia Zheng!</h2>
            <p>I am seeking an internship from September to December 2025, where I can apply the knowledge and skills I've gained in college. I also look forward to learning new experiences and contributing meaningfully to your company.</p>
        </div>
        <div class="home-image-container">
            <img src="Profile picture.png" alt="Song Jia Zheng Profile">
        </div>
    </div>
</section>

<!--About Me Section-->
<section id="about" class="section-animated">
    <h2>About Me</h2>
    <div class="about-diagram">
        <div class="about-box">
            <h3>🎓 Education</h3>
            <p>Diploma in Information Technology, SEGI College Subang Jaya</p>
        </div>
        <div class="about-box">
            <h3>🏓 Hobbies</h3>
            <p>Coding, Gaming, Learning new languages, Listen to Music, Badminton</p>
        </div>
        <div class="about-box">
            <h3>💪 Strengths</h3>
            <p>Teamwork, Communiation Skills, Open minded</p>
        </div>
        <div class="about-box">
            <h3>❌ Weaknesses</h3>
            <p>Lack of confident, Lack of patience</p>
        </div>
        <div class="about-box">
            <h3>📃 Latest CGPA</h3>
            <p>CGPA 3.09</p>
        </div>
        <div class="about-box">
            <h3>📕 Current Semester</h3>
            <p>Year 2 Sem 3</p>
        </div>
    </div>
    
    <div class="languages-container">
        <h3>🗣️ Languages</h3>
        <div class="languages-grid">
            <div class="language-item">English</div>
            <div class="language-item">Malay</div>
            <div class="language-item">Chinese</div>
        </div>
    </div>
</section>

<!--Skills Section-->
<section id="skills" class="section-animated">
    <h2>My Skills</h2>
    <div class="skills-content">
        <?php if ($skills->num_rows > 0): ?> <!--If there is any skills in the database-->
            <?php while ($s = $skills->fetch_assoc()): ?>  <!--Loop through each skill record-->
                <span class="skill-item-tag"><?= htmlspecialchars($s['skill']) ?></span> <!-- Display each skill -->
            <?php endwhile; ?> <!--End loop-->
        <?php else: ?> <!--If no skills were found-->
            <p>No skills added yet. Please add skills using the admin dashboard.</p>
        <?php endif; ?>
    </div>
</section>

<!--Projects Section-->
<section id="projects" class="section-animated">
    <h2>My Projects</h2>
    <div class="projects-grid">
        <?php while ($p = $projects->fetch_assoc()): ?> <!-- Loop through each project -->
            <!-- Re-added the project-card wrapper -->
            <div class="project-card">
                <div class="project-card-content">
                    <h3><?= htmlspecialchars($p['title']) ?></h3>  <!-- Display the project title.  -->
                    <p><?= nl2br(htmlspecialchars($p['description'])) ?></p> <!-- Display the project description -->
                    <?php if (!empty($p['link'])): ?> <!-- If a project link exists -->
                        <!-- Display button linking to the project -->
                        <a href="<?= htmlspecialchars($p['link']) ?>" class="btn" target="_blank" style="background-color: #d77818ff; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; display: inline-block; margin-top: auto;">View Project</a>
                    <?php endif; ?>  <!-- End if block for project link -->
                </div>
            </div>
        <?php endwhile; ?> <!--End while loop-->
    </div>
</section>

<!--Experience Section-->
<section id="experience" class="section-animated">
    <h2>Work Experience</h2>
    <div class="timeline">
        <?php
        $exp_count = 0; // Initialize a counter to alternate left

        // Loop through each experience record from the database
        while ($e = $experience->fetch_assoc()):
            $exp_count++; // Increment experience count

             // Alternate timeline items left/right based on odd/even count
            $timeline_class = ($exp_count % 2 == 1) ? 'timeline-item-left' : 'timeline-item-right';
        ?>
        <div class="timeline-item <?= $timeline_class ?>">
            <div class="timeline-content">
                <h3><?= htmlspecialchars($e['role']) ?></h3> <!--Display job role-->
                <p class="date"><?= htmlspecialchars($e['company']) ?> | <?= htmlspecialchars($e['year']) ?></p> <!--Display job company and year-->
                <?php
                //  split description into bullet points, handling various line endings
                $description_lines = preg_split("/\r\n|\n|\r/", htmlspecialchars($e['description']), -1, PREG_SPLIT_NO_EMPTY);

                if (count($description_lines) > 1) {
                   
                    echo '<ul>';
                    foreach ($description_lines as $line) {
                        echo '<li>' . trim($line) . '</li>'; // Trim to remove any leading/trailing whitespace
                    }
                    echo '</ul>';
                } else {
                    // If it's a single line, display as paragraph with line breaks
                    echo '<p>' . nl2br(htmlspecialchars($e['description'])) . '</p>';
                }
                ?>
            </div>
        </div>
        <?php endwhile; ?> <!--End experience loop-->
    </div>
</section>

<!--Education Section-->
<section id="education" class="section-animated">
    <h2>Educational Background</h2>
    <div class="timeline"> 
        <?php
        $edu_count = 0; // Initialize a counter to alternate timeline item positions

         // Loop through each education record from database
        while ($ed = $education->fetch_assoc()):
            $edu_count++; // Increment the education count
            // Alternate class; left for odd item, right for even
            $timeline_class = ($edu_count % 2 == 1) ? 'timeline-item-left' : 'timeline-item-right';
        ?>
        <div class="timeline-item <?= $timeline_class ?>">
            <div class="timeline-content">
                <h3><?= htmlspecialchars($ed['course']) ?></h3> <!-- Display course title -->
                <p class="date"><?= htmlspecialchars($ed['instituition']) ?> | <?= htmlspecialchars($ed['year']) ?></p>  <!-- Display institution and year -->
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<!--Certification section-->
<section id="certifications" class="section-animated">
    <h2>Courses & Certifications</h2>
    <div class="timeline">
        <?php
        $cert_count = 0;
        while ($c = $certifications->fetch_assoc()):
            $cert_count++;
            $timeline_class = ($cert_count % 2 == 1) ? 'timeline-item-left' : 'timeline-item-right';
        ?>
        <div class="timeline-item <?= $timeline_class ?>">
            <div class="timeline-content">
                <h3><?= htmlspecialchars($c['title']) ?></h3>  <!-- Display certificate title -->
                <p class="date"><?= htmlspecialchars($c['platform']) ?></p>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<!--Info Section-->
<section id="resume" class="section-animated">
    <h2>📄 Resume & Social Media</h2>
    <p>You can view or download my resume and contact with me on Social Media below.</p>
    <div class="resume-buttons">
        <!-- Updated resume download link -->
        <a href="https://drive.google.com/file/d/1LNd8B4WogfVi0RFK7XKTM-_oY8zMx_TJ/view?usp=sharing" download class="btn">View Resume</a>
        <!--LinkedIn Page-->
        <a href="https://www.linkedin.com/in/song-jia-zheng-2199aa31b" target="_blank" class="btn linkedin">LinkedIn Page</a>
        <!--Git Hub-->
        <a href="https://github.com/Jz1221" target="_blank" class="btn GitHub">GitHub</a>
    </div>
</section>

<!--Contact Section-->
<section id="contact" class="section-animated">
    <h2>Contact Me</h2>
    <?php
    // Display form submission messages (success or error)
    if (isset($_SESSION['form_message'])) {
        $message_class = ($_SESSION['form_status'] == 'success') ? 'text-green-700 bg-green-100' : 'text-red-700 bg-red-100';
        echo '<div class="p-4 rounded-md mb-4 ' . $message_class . '">';
        echo '<p>' . htmlspecialchars($_SESSION['form_message']) . '</p>';
        echo '</div>';
        // Clear the session variables after displaying to prevent them from reappearing
        unset($_SESSION['form_message']);
        unset($_SESSION['form_status']);
    }
    ?>
    <div class="contact-form">
        <form action="send_mail.php" method="post">
            <input type="text" name="name" placeholder="Your Name" required />
            <input type="email" name="email" placeholder="Your Email" required />
            <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
            <button type="submit" name="send_contact_message" class="btn">Send Message</button>
        </form>
    </div>
</section>

<footer>
    <p>&copy; <?= date('Y') ?> Song Jia Zheng. All rights reserved.</p>
    <p>Designed with passion and precision.</p>
</footer>

<script>
    // JavaScript for scroll-based section animations using Intersection Observer
    document.addEventListener('DOMContentLoaded', () => {
        const sections = document.querySelectorAll('section.section-animated'); // Select all sections with this class

        const observerOptions = {
            root: null, // The viewport is the root
            rootMargin: '0px', // No extra margin around the root
            threshold: 0.1 // Trigger when 10% of the target is visible
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // If the section is visible, add the 'visible' class to trigger CSS animation
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target); // Stop observing once it's visible
                }
            });
        }, observerOptions);

        // Start observing each section
        sections.forEach(section => {
            observer.observe(section);
        });
    });
</script>
</body>
</html>