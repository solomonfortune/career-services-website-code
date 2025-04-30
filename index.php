<?php
session_start();
require_once 'config/database.php';

// Get user data if logged in
$user = null;
if (isset($_SESSION['user_id'])) {
    $user_query = "SELECT username FROM users WHERE id = ?";
    $stmt = $conn->prepare($user_query);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Career Services</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="stylesheet" type="text/css" href="css/header.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/api-sections.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="banner">
        <img src="https://images.pexels.com/photos/3184465/pexels-photo-3184465.jpeg" alt="Career Services Banner">
        <div class="banner-text">
            <h1>Your Career Starts Here</h1>
            <p>Explore our services and get the support you need to succeed.</p>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="signup.php" class="btn">Get Started</a>
                <a href="login.php" class="btn">Login</a>
            <?php else: ?>
                <a href="dashboard.php" class="btn">My Dashboard</a>
                <a href="appointments.php" class="btn">Book Appointment</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="container">
        <section class="intro">
            <?php if (isset($_SESSION['user_id'])): ?>
                <h1>Welcome back, <?php echo htmlspecialchars($user['username']); ?>!</h1>
            <?php else: ?>
                <h1>Welcome to Career Services</h1>
            <?php endif; ?>
            <p>Your success is our mission. Explore our services to help you achieve your career goals.</p>
        </section>

        <!-- Add Job Listings Section -->
        <section class="job-listings">
            <h2>Featured Job Opportunities</h2>
            <div id="jobListings" class="jobs-grid">
                <div class="loading-spinner"></div>
            </div>
        </section>

        <!-- Add Inspirational Quote Section -->
        <div id="dailyQuote" class="quote-section">
            <div class="loading-spinner"></div>
        </div>

        <script>
            // Using RSS feed from RemoteOK jobs
            async function fetchJobs() {
                try {
                    document.getElementById('jobListings').innerHTML = '<div class="loading-spinner"></div>';
                    const response = await fetch('https://remoteok.com/api?tags=dev');
                    const data = await response.json();
                    
                    const container = document.getElementById('jobListings');
                    container.innerHTML = ''; // Clear loading spinner
                    
                    data.slice(0, 9).forEach((job, index) => {
                        if (job.position && job.company) { // Ensure valid job data
                            const jobCard = document.createElement('div');
                            jobCard.className = 'job-card';
                            jobCard.style.animationDelay = `${index * 0.1}s`;
                            jobCard.innerHTML = `
                                <h3>${job.position}</h3>
                                <p class="company">${job.company}</p>
                                <p class="location">${job.location || 'Remote'}</p>
                                <a href="${job.url}" target="_blank" class="btn-view-job">View Job</a>
                            `;
                            container.appendChild(jobCard);
                        }
                    });
                } catch (error) {
                    console.error('Error fetching jobs:', error);
                    // Fallback static job listings
                    document.getElementById('jobListings').innerHTML = getStaticJobListings();
                }
            }

            function getStaticJobListings() {
                const jobs = [
                    {title: 'Software Developer', company: 'Tech Corp', location: 'Remote'},
                    {title: 'Web Developer', company: 'Web Solutions', location: 'New York'},
                    {title: 'Frontend Engineer', company: 'Design Co', location: 'Remote'},
                    {title: 'Backend Developer', company: 'Data Systems', location: 'San Francisco'},
                    {title: 'Full Stack Developer', company: 'Innovation Labs', location: 'Remote'},
                    {title: 'DevOps Engineer', company: 'Cloud Tech', location: 'Seattle'},
                    {title: 'Mobile Developer', company: 'App Solutions', location: 'Remote'},
                    {title: 'UI/UX Developer', company: 'Creative Studio', location: 'Boston'}
                ];
                
                return jobs.map(job => `
                    <div class="job-card">
                        <h3>${job.title}</h3>
                        <p class="company">${job.company}</p>
                        <p class="location">${job.location}</p>
                        <a href="#" class="btn-apply">View Job</a>
                    </div>
                `).join('');
            }

            // Using Ninja Quotes API (free, no key required)
            async function fetchDailyQuote() {
                try {
                    const response = await fetch('https://api.quotable.io/random?tags=success,inspirational');
                    const data = await response.json();
                    
                    document.getElementById('dailyQuote').innerHTML = `
                        <blockquote>
                            <p>"${data.content}"</p>
                            <footer>— ${data.author}</footer>
                        </blockquote>
                    `;
                } catch (error) {
                    console.error('Error fetching quote:', error);
                    // Fallback content
                    document.getElementById('dailyQuote').innerHTML = `
                        <blockquote>
                            <p>"Success is not final, failure is not fatal: it is the courage to continue that counts."</p>
                            <footer>— Winston Churchill</footer>
                        </blockquote>
                    `;
                }
            }

            fetchJobs();
            fetchDailyQuote();
        </script>

        <section class="services">
            <h2>Our Services</h2>
            <div class="service-row">
                <div class="service-item">
                    <i class="fas fa-briefcase fa-3x"></i>
                    <h3>Job Matching</h3>
                    <p>We help you find the right job that matches your skills and interests.</p>
                </div>
                <div class="service-item">
                    <i class="fas fa-compass fa-3x"></i>
                    <h3>Career Guidance</h3>
                    <p>Get expert advice on your career path and professional development.</p>
                </div>
                <div class="service-item">
                    <i class="fas fa-book fa-3x"></i>
                    <h3>Learning Resources</h3>
                    <p>Access a variety of resources to enhance your skills and knowledge.</p>
                </div>
                <div class="service-item">
                    <i class="fas fa-network-wired fa-3x"></i>
                    <h3>Network Building</h3>
                    <p>Connect with professionals and expand your network.</p>
                </div>
            </div>
        </section>
        <section class="events">
            <h2>Upcoming Events</h2>
            <p>Join our upcoming career events and workshops.</p>
        </section>
        <section class="contact">
            <h2>Contact Us</h2>
            <p>Get in touch with our career advisors for personalized support.</p>
        </section>
    </div>
    <?php 
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
    include 'footer.php'; 
    ?>
</body>
</html>
