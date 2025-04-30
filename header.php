<!DOCTYPE html>
<html>
<head>
    <title>Career Services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="logo">
            <i class="fas fa-briefcase"></i>
            <span class="logo-text">Career<span class="highlight">Services</span></span>
        </div>
        <nav>
            <a href="index.php">Home</a> |
            <?php if(!isset($_SESSION['user_id'])): ?>
                <a href="signup.php">Sign Up</a> |
                <a href="login.php">Login</a> |
            <?php else: ?>
                <a href="dashboard.php">Dashboard</a> |
                <a href="appointments.php">Appointments</a> |
                <a href="logout.php">Logout</a> |
            <?php endif; ?>
            <a href="services.php">Our Services</a>
        </nav>
    </header>
</body>
</html>
