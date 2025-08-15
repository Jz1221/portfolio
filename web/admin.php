<?php

// This ensures session_start() is called only if a session is not already active.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'connection.php';

// --- CRUD: Projects ---
if (isset($_POST['add_project'])) {
    $conn->query("INSERT INTO projects (title, description, link) VALUES (
        '".addslashes($_POST['title'])."',
        '".addslashes($_POST['description'])."',
        '".addslashes($_POST['link'])."')");
}
// Update Project
if (isset($_POST['update_project'])) {
    $id = (int)$_POST['project_id']; // Get the ID of the project to update
    $title = addslashes($_POST['title']);
    $description = addslashes($_POST['description']);
    $link = addslashes($_POST['link']);
    $conn->query("UPDATE projects SET title='$title', description='$description', link='$link' WHERE id=$id");
}
if (isset($_POST['delete_project'])) {
    $conn->query("DELETE FROM projects WHERE id=".(int)$_POST['delete_project']);
}

// --- CRUD: Skills ---
if (isset($_POST['add_skill'])) {
    $conn->query("INSERT INTO skills (skill) VALUES ('".addslashes($_POST['skill'])."')");
}
// Update Skill
if (isset($_POST['update_skill'])) {
    $id = (int)$_POST['skill_id']; // Get the ID of the skill to update
    $skill = addslashes($_POST['skill']);
    $conn->query("UPDATE skills SET skill='$skill' WHERE id=$id");
}
if (isset($_POST['delete_skill'])) {
    $conn->query("DELETE FROM skills WHERE id=".(int)$_POST['delete_skill']);
}

// --- CRUD: Experience ---
if (isset($_POST['add_experience'])) {
    $conn->query("INSERT INTO experience (role, company, year, description) VALUES (
        '".addslashes($_POST['role'])."',
        '".addslashes($_POST['company'])."',
        '".addslashes($_POST['year'])."',
        '".addslashes($_POST['description'])."')");
}
// Update Experience
if (isset($_POST['update_experience'])) {
    $id = (int)$_POST['experience_id']; // Get the ID of the experience to update
    $role = addslashes($_POST['role']);
    $company = addslashes($_POST['company']);
    $year = addslashes($_POST['year']);
    $description = addslashes($_POST['description']);
    $conn->query("UPDATE experience SET role='$role', company='$company', year='$year', description='$description' WHERE id=$id");
}
if (isset($_POST['delete_experience'])) {
    $conn->query("DELETE FROM experience WHERE id=".(int)$_POST['delete_experience']);
}

// --- CRUD: Education ---
if (isset($_POST['add_education'])) {
    $conn->query("INSERT INTO education (course, instituition, year) VALUES (
        '".addslashes($_POST['course'])."',
        '".addslashes($_POST['instituition'])."',
        '".addslashes($_POST['year'])."')");
}
// Update Education
if (isset($_POST['update_education'])) {
    $id = (int)$_POST['education_id']; // Get the ID of the education entry to update
    $course = addslashes($_POST['course']);
    $instituition = addslashes($_POST['instituition']);
    $year = addslashes($_POST['year']);
    $conn->query("UPDATE education SET course='$course', instituition='$instituition', year='$year' WHERE id=$id");
}
if (isset($_POST['delete_education'])) {
    $conn->query("DELETE FROM education WHERE id=".(int)$_POST['delete_education']);
}

// --- CRUD: Certifications ---
if (isset($_POST['add_certification'])) {
    $conn->query("INSERT INTO certifications (title, platform) VALUES (
        '".addslashes($_POST['cert_title'])."',
        '".addslashes($_POST['platform'])."')");
}
// Update Certification
if (isset($_POST['update_certification'])) {
    $id = (int)$_POST['certification_id']; // Get the ID of the certification to update
    $title = addslashes($_POST['cert_title']);
    $platform = addslashes($_POST['platform']);
    $conn->query("UPDATE certifications SET title='$title', platform='$platform' WHERE id=$id");
}
if (isset($_POST['delete_certification'])) {
    $conn->query("DELETE FROM certifications WHERE id=".(int)$_POST['delete_certification']);
}

// --- Fetch all data ---
$projects       = $conn->query("SELECT * FROM projects ORDER BY id DESC");
$skills         = $conn->query("SELECT * FROM skills ORDER BY id DESC");
$experience     = $conn->query("SELECT * FROM experience ORDER BY id DESC");
$education      = $conn->query("SELECT * FROM education ORDER BY id DESC");
$certifications = $conn->query("SELECT * FROM certifications ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Song Jia Zheng Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="admin.css">
</head>
<body class="p-8">

  <header class="text-center mb-10 bg-white p-6 rounded-lg shadow-lg">
    <h1 class="text-5xl font-extrabold text-gray-800 mb-4 animate-fade-in-down">Song Jia Zheng Admin Dashboard</h1>
    <p class="text-xl text-gray-600 mb-6 animate-fade-in-up">Create, Read, Update & Delete your portfolio content dashboard.</p>
    <div class="flex justify-center gap-6 flex-wrap">
      <a href="sjz.php" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200 shadow-md">
        🏠 Home Page
      </a>
      <a href="sjz.php" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 transition-colors duration-200 shadow-md">
        👩‍💻 View My Portfolio
      </a>
      <button onclick="location.reload()" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 transition-colors duration-200 shadow-md">
        🔄 Reload My Admin Dashboard
      </button>
    </div>
  </header>

  <main class="max-w-7xl mx-auto">
    <!-- Tab Buttons -->
    <div class="tab-buttons">
      <button class="tab-button active" onclick="openTab(event, 'projects')">View Projects</button>
      <button class="tab-button" onclick="openTab(event, 'skills')">View Skills</button>
      <button class="tab-button" onclick="openTab(event, 'experience')">View Experience</button>
      <button class="tab-button" onclick="openTab(event, 'education')">View Education</button>
      <button class="tab-button" onclick="openTab(event, 'certifications')">View Certifications</button>
    </div>

    <!-- Tab Content Containers -->

    <!-- Projects Tab Content -->
    <div id="projects" class="tab-content visible-content">
        <div class="data-card section-projects">
            <h2 class="text-3xl font-bold text-color mb-6">My Projects</h2>
            <form method="POST" class="space-y-4 mb-6 p-6 rounded-lg form-bg border">
                <input name="title" placeholder="Title" class="w-full p-3 border border-gray-300 rounded-md focus:ring-2" required>
                <textarea name="description" placeholder="Description" rows="4" class="w-full p-3 border border-gray-300 rounded-md focus:ring-2" required></textarea>
                <input name="link" placeholder="Project Link (optional)" class="w-full p-3 border border-gray-300 rounded-md focus:ring-2">
                <button name="add_project" class="w-full text-white font-bold py-3 px-4 rounded-md btn-add btn-primary">Add Project</button>
            </form>

            <?php while ($p = $projects->fetch_assoc()): ?>
                <div class="data-card animate-fade-in-up">
                    <!-- Display Area -->
                    <div id="project-display-<?= $p['id'] ?>" class="visible-content">
                        <div class="font-bold text-xl text-gray-900 mb-2"><?= htmlspecialchars($p['title']) ?></div>
                        <div class="text-gray-700 text-base mb-3"><?= nl2br(htmlspecialchars($p['description'])) ?></div>
                        <?php if (!empty($p['link'])): ?><a href="<?= htmlspecialchars($p['link']) ?>" target="_blank" class="text-blue-600 hover:underline text-sm block mb-4">Link: <?= htmlspecialchars($p['link']) ?></a><?php endif; ?>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="toggleEditForm('project', <?= $p['id'] ?>)" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md text-sm btn-primary">Edit</button>
                            <form method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this project?');">
                                <button name="delete_project" value="<?= $p['id'] ?>" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-md text-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>

                    <!-- Edit Form Area (Initially hidden) -->
                    <form method="POST" id="project-edit-<?= $p['id'] ?>" class="space-y-4 pt-6 mt-6 border-t border-gray-200 hidden-content">
                        <input type="hidden" name="project_id" value="<?= $p['id'] ?>">
                        <input name="title" placeholder="New Title" value="<?= htmlspecialchars($p['title']) ?>" class="w-full p-3 border border-gray-300 rounded-md text-base focus:ring-2">
                        <textarea name="description" placeholder="New Description" rows="4" class="w-full p-3 border border-gray-300 rounded-md text-base focus:ring-2"><?= htmlspecialchars($p['description']) ?></textarea>
                        <input name="link" placeholder="New Project Link" value="<?= htmlspecialchars($p['link']) ?>" class="w-full p-3 border border-gray-300 rounded-md text-base focus:ring-2">
                        <div class="flex justify-end space-x-3">
                            <button name="update_project" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-md text-sm btn-primary">Save</button>
                            <button type="button" onclick="toggleEditForm('project', <?= $p['id'] ?>)" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-md text-sm btn-secondary">Cancel</button>
                        </div>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Skills Tab Content -->
    <div id="skills" class="tab-content hidden-content">
        <div class="data-card section-skills">
            <h2 class="text-3xl font-bold text-color mb-6">My Skills</h2>
            <form method="POST" class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3 mb-6 p-6 rounded-lg form-bg border">
                <input name="skill" placeholder="Skill name" class="flex-grow p-3 border border-gray-300 rounded-md focus:ring-2" required>
                <button name="add_skill" class="flex-shrink-0 text-white font-bold py-3 px-4 rounded-md btn-add btn-primary">Add Skill</button>
            </form>
            <?php while ($s = $skills->fetch_assoc()): ?>
                <div class="data-card animate-fade-in-up">
                    <!-- Display Area -->
                    <div id="skill-display-<?= $s['id'] ?>" class="visible-content">
                        <span class="font-bold text-lg text-gray-900"><?= htmlspecialchars($s['skill']) ?></span>
                        <div class="flex space-x-3">
                            <button type="button" onclick="toggleEditForm('skill', <?= $s['id'] ?>)" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md text-sm btn-primary">Edit</button>
                            <form method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this skill?');">
                                <button name="delete_skill" value="<?= $s['id'] ?>" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-md text-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>

                    <!-- Edit Form Area (Initially hidden) -->
                    <form method="POST" id="skill-edit-<?= $s['id'] ?>" class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3 items-center pt-6 mt-6 border-t border-gray-200 hidden-content">
                        <input type="hidden" name="skill_id" value="<?= $s['id'] ?>">
                        <input type="text" name="skill" value="<?= htmlspecialchars($s['skill']) ?>" class="flex-grow p-3 border border-gray-300 rounded-md text-base focus:ring-2">
                        <button name="update_skill" class="flex-shrink-0 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-md text-sm btn-primary">Save</button>
                        <button type="button" onclick="toggleEditForm('skill', <?= $s['id'] ?>)" class="flex-shrink-0 bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-md text-sm btn-secondary">Cancel</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Experience Tab Content -->
    <div id="experience" class="tab-content hidden-content">
        <div class="data-card section-experience">
            <h2 class="text-3xl font-bold text-color mb-6">My Experience</h2>
            <form method="POST" class="space-y-4 mb-6 p-6 rounded-lg form-bg border">
                <input name="role" placeholder="Role" class="w-full p-3 border border-gray-300 rounded-md focus:ring-2" required>
                <input name="company" placeholder="Company" class="w-full p-3 border border-gray-300 rounded-md focus:ring-2" required>
                <input name="year" placeholder="Year" class="w-full p-3 border border-gray-300 rounded-md focus:ring-2" required>
                <textarea name="description" placeholder="Description" rows="4" class="w-full p-3 border border-gray-300 rounded-md focus:ring-2" required></textarea>
                <button name="add_experience" class="w-full text-white font-bold py-3 px-4 rounded-md btn-add btn-primary">Add Experience</button>
            </form>
            <?php while ($e = $experience->fetch_assoc()): ?>
                <div class="data-card animate-fade-in-up">
                    <!-- Display Area -->
                    <div id="experience-display-<?= $e['id'] ?>" class="visible-content">
                        <div class="font-bold text-xl text-gray-900 mb-2"><?= htmlspecialchars($e['role']) ?></div>
                        <div class="text-gray-700 text-base mb-1"><?= htmlspecialchars($e['company']) ?> | <?= htmlspecialchars($e['year']) ?></div>
                        <div class="text-gray-700 text-sm mb-3"><?= nl2br(htmlspecialchars($e['description'])) ?></div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="toggleEditForm('experience', <?= $e['id'] ?>)" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md text-sm btn-primary">Edit</button>
                            <form method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this experience?');">
                                <button name="delete_experience" value="<?= $e['id'] ?>" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-md text-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>

                    <!-- Edit Form Area (Initially hidden) -->
                    <form method="POST" id="experience-edit-<?= $e['id'] ?>" class="space-y-4 pt-6 mt-6 border-t border-gray-200 hidden-content">
                        <input type="hidden" name="experience_id" value="<?= $e['id'] ?>">
                        <input name="role" placeholder="New Role" value="<?= htmlspecialchars($e['role']) ?>" class="w-full p-3 border border-gray-300 rounded-md text-base focus:ring-2">
                        <input name="company" placeholder="New Company" value="<?= htmlspecialchars($e['company']) ?>" class="w-full p-3 border border-gray-300 rounded-md text-base focus:ring-2">
                        <input name="year" placeholder="New Year" value="<?= htmlspecialchars($e['year']) ?>" class="w-full p-3 border border-gray-300 rounded-md text-base focus:ring-2">
                        <textarea name="description" placeholder="New Description" rows="4" class="w-full p-3 border border-gray-300 rounded-md text-base focus:ring-2"><?= htmlspecialchars($e['description']) ?></textarea>
                        <div class="flex justify-end space-x-3">
                            <button name="update_experience" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-md text-sm btn-primary">Save</button>
                            <button type="button" onclick="toggleEditForm('experience', <?= $e['id'] ?>)" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-md text-sm btn-secondary">Cancel</button>
                        </div>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Education Tab Content -->
    <div id="education" class="tab-content hidden-content">
        <div class="data-card section-education">
            <h2 class="text-3xl font-bold text-color mb-6">My Education</h2>
            <form method="POST" class="space-y-4 mb-6 p-6 rounded-lg form-bg border">
                <input name="course" placeholder="Course" class="w-full p-3 border border-gray-300 rounded-md focus:ring-2" required>
                <input name="instituition" placeholder="Institution" class="w-full p-3 border border-gray-300 rounded-md focus:ring-2" required>
                <input name="year" placeholder="Year" class="w-full p-3 border border-gray-300 rounded-md focus:ring-2" required>
                <button name="add_education" class="w-full text-white font-bold py-3 px-4 rounded-md btn-add btn-primary">Add Education</button>
            </form>
            <?php while ($ed = $education->fetch_assoc()): ?>
                <div class="data-card animate-fade-in-up">
                    <!-- Display Area -->
                    <div id="education-display-<?= $ed['id'] ?>" class="visible-content">
                        <div class="font-bold text-xl text-gray-900 mb-2"><?= htmlspecialchars($ed['course']) ?></div>
                        <div class="text-gray-700 text-base mb-3"><?= htmlspecialchars($ed['instituition']) ?> | <?= htmlspecialchars($ed['year']) ?></div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="toggleEditForm('education', <?= $ed['id'] ?>)" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md text-sm btn-primary">Edit</button>
                            <form method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this education entry?');">
                                <button name="delete_education" value="<?= $ed['id'] ?>" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-md text-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>

                    <!-- Edit Form Area (Initially hidden) -->
                    <form method="POST" id="education-edit-<?= $ed['id'] ?>" class="space-y-4 pt-6 mt-6 border-t border-gray-200 hidden-content">
                        <input type="hidden" name="education_id" value="<?= $ed['id'] ?>">
                        <input name="course" placeholder="New Course" value="<?= htmlspecialchars($ed['course']) ?>" class="w-full p-3 border border-gray-300 rounded-md text-base focus:ring-2">
                        <input name="instituition" placeholder="New Institution" value="<?= htmlspecialchars($ed['instituition']) ?>" class="w-full p-3 border border-gray-300 rounded-md text-base focus:ring-2">
                        <input name="year" placeholder="New Year" value="<?= htmlspecialchars($ed['year']) ?>" class="w-full p-3 border border-gray-300 rounded-md text-base focus:ring-2">
                        <div class="flex justify-end space-x-3">
                            <button name="update_education" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-md text-sm btn-primary">Save</button>
                            <button type="button" onclick="toggleEditForm('education', <?= $ed['id'] ?>)" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-md text-sm btn-secondary">Cancel</button>
                        </div>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Certifications Tab Content -->
    <div id="certifications" class="tab-content hidden-content">
        <div class="data-card section-certifications">
            <h2 class="text-3xl font-bold text-color mb-6">My Certifications</h2>
            <form method="POST" class="space-y-4 mb-6 p-6 rounded-lg form-bg border">
                <input name="cert_title" placeholder="Certification Title" class="w-full p-3 border border-gray-300 rounded-md focus:ring-2" required>
                <input name="platform" placeholder="Platform" class="w-full p-3 border border-gray-300 rounded-md focus:ring-2" required>
                <button name="add_certification" class="w-full text-white font-bold py-3 px-4 rounded-md btn-add btn-primary">Add Certification</button>
            </form>
            <?php while ($c = $certifications->fetch_assoc()): ?>
                <div class="data-card animate-fade-in-up">
                    <!-- Display Area -->
                    <div id="certification-display-<?= $c['id'] ?>" class="visible-content">
                        <div class="font-bold text-xl text-gray-900 mb-2"><?= htmlspecialchars($c['title']) ?></div>
                        <div class="text-gray-700 text-base mb-3"><?= htmlspecialchars($c['platform']) ?></div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="toggleEditForm('certification', <?= $c['id'] ?>)" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md text-sm btn-primary">Edit</button>
                            <form method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this certification?');">
                                <button name="delete_certification" value="<?= $c['id'] ?>" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-md text-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>

                    <!-- Edit Form Area (Initially hidden) -->
                    <form method="POST" id="certification-edit-<?= $c['id'] ?>" class="space-y-4 pt-6 mt-6 border-t border-gray-200 hidden-content">
                        <input type="hidden" name="certification_id" value="<?= $c['id'] ?>">
                        <input name="cert_title" placeholder="New Certification Title" value="<?= htmlspecialchars($c['title']) ?>" class="w-full p-3 border border-gray-300 rounded-md text-base focus:ring-2">
                        <input name="platform" placeholder="New Platform" value="<?= htmlspecialchars($c['platform']) ?>" class="w-full p-3 border border-gray-300 rounded-md text-base focus:ring-2">
                        <div class="flex justify-end space-x-3">
                            <button name="update_certification" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-md text-sm btn-primary">Save</button>
                            <button type="button" onclick="toggleEditForm('certification', <?= $c['id'] ?>)" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-md text-sm btn-secondary">Cancel</button>
                        </div>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

  </main>

<script>
    // Function to handle tab switching
    function openTab(evt, tabName) {
        let i, tabcontent, tabbuttons;

        // Get all elements with class="tab-content" and hide them
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].classList.remove('visible-content');
            tabcontent[i].classList.add('hidden-content');
        }

        // Get all elements with class="tab-button" and remove the "active" class
        tabbuttons = document.getElementsByClassName("tab-button");
        for (i = 0; i < tabbuttons.length; i++) {
            tabbuttons[i].classList.remove("active");
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(tabName).classList.remove('hidden-content');
        document.getElementById(tabName).classList.add('visible-content');
        evt.currentTarget.classList.add("active");

        // Re-trigger animations for cards in the newly active tab
        const activeTabCards = document.getElementById(tabName).querySelectorAll('.data-card');
        activeTabCards.forEach((card) => {
            card.classList.remove('animate-fade-in-up'); // Remove to allow re-trigger
            void card.offsetWidth; // Trigger reflow
            card.classList.add('animate-fade-in-up'); // Add back to animate
        });
    }

    // Function to toggle between display and edit form for each item
    function toggleEditForm(type, id) {
      const displayElement = document.getElementById(`${type}-display-${id}`);
      const editFormElement = document.getElementById(`${type}-edit-${id}`);

      // Toggle 'hidden-content' and 'visible-content' classes
      if (displayElement.classList.contains('visible-content') || !displayElement.classList.contains('hidden-content')) {
        // If currently visible (or default state), hide display and show form
        displayElement.classList.remove('visible-content');
        displayElement.classList.add('hidden-content');
        editFormElement.classList.remove('hidden-content');
        editFormElement.classList.add('visible-content');
      } else {
        // If currently hidden, show display and hide form
        displayElement.classList.remove('hidden-content');
        displayElement.classList.add('visible-content');
        editFormElement.classList.remove('visible-content');
        editFormElement.classList.add('hidden-content');
      }
    }

    // Set the default active tab on page load (Projects)
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelector('.tab-button').click(); // Simulate click on the first tab
    });
</script>
</body>
</html>