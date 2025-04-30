<?php
session_start();
require_once 'config/database.php';

// Fetch all services from database
$services_query = "SELECT * FROM services ORDER BY name";
$services_result = $conn->query($services_query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Our Services - Career Services</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="stylesheet" type="text/css" href="css/services.css">
    <link rel="stylesheet" type="text/css" href="css/header.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="services-banner">
        <h1>Our Services</h1>
        <p>Empowering your career journey with comprehensive professional support</p>
    </div>

    <div class="container">
        <div class="services-grid">
            <?php while($service = $services_result->fetch_assoc()): ?>
                <div class="service-card">
                    <div class="service-content">
                        <div class="service-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <h2 class="service-title"><?php echo htmlspecialchars($service['name']); ?></h2>
                        <div class="service-description">
                            <p><?php echo htmlspecialchars($service['description']); ?></p>
                        </div>
                        <div class="service-details">
                            <span><i class="fas fa-clock"></i> <?php echo $service['duration']; ?> mins</span>
                            <span><i class="fas fa-dollar-sign"></i> <?php echo number_format($service['price'], 2); ?></span>
                        </div>
                    </div>
                    <div class="service-footer">
                        <a href="appointments.php?service=<?php echo urlencode($service['name']); ?>" class="service-btn">Book Now</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <section class="why-choose-us">
            <h2>Why Choose Our Services?</h2>
            <div class="features-grid">
                <div class="feature">
                    <i class="fas fa-check-circle"></i>
                    <h3>Professional Expertise</h3>
                    <p>Our team consists of certified career counselors</p>
                </div>
                <div class="feature">
                    <i class="fas fa-clock"></i>
                    <h3>Dedicated Support</h3>
                    <p>24/7 assistance for your career needs</p>
                </div>
                <div class="feature">
                    <i class="fas fa-chart-line"></i>
                    <h3>Proven Results</h3>
                    <p>High success rate in career placements</p>
                </div>
            </div>
        </section>
    </div>

    <?php 
    $conn->close();
    include 'footer.php'; 
    ?>
</body>
</html>
