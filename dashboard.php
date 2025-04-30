<?php

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'config/database.php';

// Use session user_id instead of hardcoded value
$user_id = $_SESSION['user_id'];

// Rest of the queries now use $user_id from session
$user_query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// Update other queries similarly
$appointments_query = "SELECT COUNT(*) as count FROM appointments WHERE user_id = ? AND appointment_date >= CURDATE()";
$stmt = $conn->prepare($appointments_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$appointments_result = $stmt->get_result();
$appointments_count = $appointments_result->fetch_assoc()['count'];

// Fetch resources accessed
$resources_accessed = 12; // Placeholder value

// Fetch tasks completed
$tasks_completed = 8; // Placeholder value

// Fetch achievement points
$achievement_points = 150; // Placeholder value

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Career Services</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="css/header.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <link rel="stylesheet" type="text/css" href="css/settings.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/api-sections.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <aside class="dashboard-sidebar">
            <div class="user-profile">
                <div class="profile-image-container">
                    <img src="<?php echo !empty($user['profile_pic_path']) ? $user['profile_pic_path'] : 'images/default-avatar.png'; ?>" alt="User Profile">
                    <div class="profile-image-overlay">
                        <label for="quick-upload" class="upload-btn">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" id="quick-upload" style="display: none" accept="image/*">
                    </div>
                </div>
                <h3>Welcome, <?php echo $user['username']; ?></h3>
                <p>Student</p>
            </div>
            
            <nav class="dashboard-nav">
                <a href="javascript:void(0)" data-section="overview" class="nav-link active"><i class="fas fa-home"></i> Overview</a>
                <a href="javascript:void(0)" data-section="settings" class="nav-link"><i class="fas fa-cog"></i> Settings</a>
                <a href="logout.php" class="nav-link" id="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="dashboard-main">
            <!-- Overview Section -->
            <section class="dashboard-section active" id="overview">
                <h2>Dashboard Overview</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <i class="fas fa-calendar-check"></i>
                        <h3>Upcoming Appointments</h3>
                        <p class="stat-number"><?php echo $appointments_count; ?></p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-file-alt"></i>
                        <h3>Resources Accessed</h3>
                        <p class="stat-number"><?php echo $resources_accessed; ?></p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-tasks"></i>
                        <h3>Tasks Completed</h3>
                        <p class="stat-number"><?php echo $tasks_completed; ?></p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-star"></i>
                        <h3>Achievement Points</h3>
                        <p class="stat-number"><?php echo $achievement_points; ?></p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <h3>Quick Actions</h3>
                    <div class="action-buttons">
                        <a href="appointments.php" class="action-btn">
                            <i class="fas fa-plus"></i> New Appointment
                        </a>
                        <a href="resources.php" class="action-btn">
                            <i class="fas fa-download"></i> Download Resources
                        </a>
                        <a href="profile.php" class="action-btn">
                            <i class="fas fa-user-edit"></i> Update Profile
                        </a>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="recent-activity">
                    <h3>Recent Activity</h3>
                    <div class="activity-list">
                        <div class="activity-item">
                            <i class="fas fa-check-circle"></i>
                            <div class="activity-content">
                                <p>Completed Resume Review Session</p>
                                <span>2 days ago</span>
                            </div>
                        </div>
                        <div class="activity-item">
                            <i class="fas fa-download"></i>
                            <div class="activity-content">
                                <p>Downloaded Interview Preparation Guide</p>
                                <span>3 days ago</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GitHub & News sections -->
                <div class="github-activity">
                    <h3>GitHub Activity</h3>
                    <div id="githubFeed">
                        <div class="loading-spinner"></div>
                    </div>
                </div>

                <div class="career-news">
                    <h3>Latest Career News</h3>
                    <div id="newsContainer">
                        <div class="loading-spinner"></div>
                    </div>
                </div>

                <script>
                    // Using GitHub Public API
                    async function fetchGitHubActivity(username) {
                        try {
                            const response = await fetch(`https://api.github.com/users/${username}/repos`);
                            const data = await response.json();
                            
                            const container = document.getElementById('githubFeed');
                            container.innerHTML = ''; // Clear loading spinner
                            
                            data.slice(0, 5).forEach((repo, index) => {
                                const card = document.createElement('div');
                                card.className = 'github-card activity-item';
                                card.style.animationDelay = `${index * 0.1}s`;
                                card.innerHTML = `
                                    <i class="fab fa-github"></i>
                                    <div class="activity-content">
                                        <p>${repo.name}</p>
                                        <span>${repo.description || 'No description available'}</span>
                                    </div>
                                `;
                                container.appendChild(card);
                            });
                        } catch (error) {
                            console.error('Error fetching GitHub activity:', error);
                            document.getElementById('githubFeed').innerHTML = '<p>Unable to load GitHub activity</p>';
                        }
                    }

                    // Using NewsAPI (free tier)
                    async function fetchCareerNews() {
                        try {
                            // Using NewsData.io API with career-specific keywords
                            const API_KEY = 'pub_586222b3840514e29b2e10de23a79b7107c25';
                            const response = await fetch(
                                `https://newsdata.io/api/1/news?` +
                                `apikey=${API_KEY}&` +
                                `q=career%20development%20OR%20job%20market%20OR%20employment%20trends%20OR%20workplace&` +
                                `category=business&` + // Focus on business category
                                `language=en&` +
                                `size=3`
                            );
                            const data = await response.json();
                            
                            const container = document.getElementById('newsContainer');
                            container.innerHTML = ''; // Clear loading spinner
                            
                            if (data.results) {
                                data.results.forEach((article, index) => {
                                    // Filter articles that are more likely to be career-related
                                    if (isCareerRelated(article.title) || isCareerRelated(article.description)) {
                                        const card = document.createElement('div');
                                        card.className = 'news-card';
                                        card.style.animationDelay = `${index * 0.1}s`;
                                        card.innerHTML = `
                                            <div class="news-card-content">
                                                <h4>${article.title}</h4>
                                                <p>${article.description || ''}</p>
                                                <div class="news-meta">
                                                    <span><i class="far fa-calendar"></i> ${new Date(article.pubDate).toLocaleDateString()}</span>
                                                    <span><i class="far fa-newspaper"></i> ${article.source_id}</span>
                                                </div>
                                            </div>
                                            <a href="${article.link}" target="_blank" class="btn-read-more">
                                                Read More <i class="fas fa-arrow-right"></i>
                                            </a>
                                        `;
                                        container.appendChild(card);
                                    }
                                });
                            } else {
                                throw new Error('No news data available');
                            }

                            // If no career-related articles were found, show static content
                            if (container.children.length === 0) {
                                container.innerHTML = getStaticNewsContent();
                            }
                        } catch (error) {
                            console.error('Error fetching news:', error);
                            document.getElementById('newsContainer').innerHTML = getStaticNewsContent();
                        }
                    }

                    // Helper function to check if content is career-related
                    function isCareerRelated(text) {
                        if (!text) return false;
                        const careerKeywords = [
                            'career', 'job', 'employment', 'workplace', 'hiring',
                            'work', 'profession', 'salary', 'recruit', 'skill',
                            'resume', 'interview', 'remote work', 'office',
                            'business', 'industry', 'professional', 'workforce'
                        ];
                        text = text.toLowerCase();
                        return careerKeywords.some(keyword => text.includes(keyword.toLowerCase()));
                    }

                    function getStaticNewsContent() {
                        return `
                            <div class="news-card">
                                <div class="news-card-content">
                                    <h4>Remote Work Trends Reshaping Career Opportunities</h4>
                                    <p>New study reveals how remote work is transforming career paths and creating new opportunities across industries.</p>
                                    <div class="news-meta">
                                        <span><i class="far fa-calendar"></i> ${new Date().toLocaleDateString()}</span>
                                        <span><i class="far fa-newspaper"></i> Career Insights</span>
                                    </div>
                                </div>
                                <a href="#" class="btn-read-more">Read More <i class="fas fa-arrow-right"></i></a>
                            </div>
                            <div class="news-card">
                                <div class="news-card-content">
                                    <h4>Top Skills Employers Are Looking for in 2024</h4>
                                    <p>Latest industry report highlights the most in-demand professional skills in today's job market.</p>
                                    <div class="news-meta">
                                        <span><i class="far fa-calendar"></i> ${new Date().toLocaleDateString()}</span>
                                        <span><i class="far fa-newspaper"></i> Career Guide</span>
                                    </div>
                                </div>
                                <a href="#" class="btn-read-more">Read More <i class="fas fa-arrow-right"></i></a>
                            </div>
                        `;
                    }

                    // Initialize with your GitHub username
                    fetchGitHubActivity('your-github-username');
                    fetchCareerNews();
                </script>
            </section>

            <!-- Settings Section -->
            <section class="dashboard-section" id="settings">
                <div id="settings-content">
                    <h2>Profile Settings</h2>
                    <div class="settings-form-container">
                        <form action="handlers/update_profile_picture.php" method="post" enctype="multipart/form-data">
                            <div class="current-profile-pic">
                                <img src="<?php echo !empty($user['profile_pic_path']) ? $user['profile_pic_path'] : 'images/default-avatar.png'; ?>" alt="Current Profile Picture">
                            </div>
                            <div class="form-group">
                                <label for="profile_picture">Update Profile Picture</label>
                                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" required>
                                <small>Accepted formats: JPG, PNG, GIF (Max size: 5MB)</small>
                            </div>
                            <button type="submit" class="btn-submit">Update Picture</button>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all nav links
            const navLinks = document.querySelectorAll('.nav-link');
            
            // Add click event to each nav link
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const section = this.getAttribute('data-section');
                    
                    if (section === undefined) return; // For logout link
                    
                    // Remove active class from all links and sections
                    navLinks.forEach(l => l.classList.remove('active'));
                    document.querySelectorAll('.dashboard-section').forEach(s => s.classList.remove('active'));
                    
                    // Add active class to clicked link and corresponding section
                    this.classList.add('active');
                    document.getElementById(section).classList.add('active');
                    
                    // Load content via AJAX
                    if (section !== 'overview') {
                        loadSectionContent(section);
                    }
                });
            });
            
            // Function to load section content
            function loadSectionContent(section) {
                const contentDiv = document.getElementById(section + '-content');
                fetch(`ajax/${section}.php`)
                    .then(response => response.text())
                    .then(html => {
                        contentDiv.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error loading content:', error);
                        contentDiv.innerHTML = 'Error loading content';
                    });
            }

            // Add this to your existing script
            document.getElementById('logout-link').addEventListener('click', function(e) {
                if(confirm('Are you sure you want to logout?')) {
                    window.location.href = 'logout.php';
                } else {
                    e.preventDefault();
                }
            });

            document.getElementById('quick-upload').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const formData = new FormData();
                    formData.append('profile_picture', file);

                    fetch('handlers/update_profile_picture.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(() => {
                        // Reload the page to show the new image
                        location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to upload image. Please try again.');
                    });
                }
            });
        });
    </script>
    <?php include 'footer.php'; ?>
</body>
</html> 
