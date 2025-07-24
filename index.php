<?php
include 'includes/header.php';
include 'config.php'; // Ensure the database connection is included

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!-- Main Content -->
<main class="container-fluid p-0" id="mainContent" style="font-family: 'Poppins', sans-serif;">
    <!-- Hero Section -->
    <section id="home" class="hero-section text-center text-white d-flex justify-content-center align-items-center">
        <div class="overlay"></div>
        <div class="content">
            <h1 class="display-4 animated-title">Experience Luxury at The Staylink Hotel</h1>
            <p class="lead animated-description">Where comfort, elegance, and style blend seamlessly for an unforgettable stay.</p>
            <a href="#services" class="btn btn-lg btn-outline-light mt-4 scroll-link">Discover Our Services</a>
        </div>
    </section>
</main>

<!-- Sections for all users (not logged in) -->
<?php if (!$isLoggedIn): ?>
    <?php include 'templates/info.php'; ?>
    <?php include 'templates/room_types.php'; ?>
    <?php include 'templates/aboutus.php'; ?>
    <?php include 'templates/gallery.php'; ?>
    <?php include 'templates/q&a.php'; ?>
    <?php include 'templates/special_events.php'; ?>
    <?php include 'templates/promotions.php'; ?>
<?php endif; ?>

<!-- Sections for logged-in users -->
<?php if ($isLoggedIn): ?>
    <?php include 'templates/menu.php'; ?>
    <?php include 'templates/reservations.php'; ?>
    <?php include 'templates/event.php'; ?>
    <?php include 'templates/booking.php'; ?>
    <?php include 'templates/rentals.php'; ?>
    
<?php else: ?>
    <div class="container d-flex justify-content-center align-items-center vh-50">
        <div class="card access-card shadow-lg border-0 rounded-4 p-5 text-center" data-aos="fade-up">
            <div class="card-body">
                <!-- Animated Lock Icon -->
                <div class="lock-icon">
                    <i class="bi bi-lock-fill text-danger"></i>
                </div>

                <!-- Title & Description -->
                <h2 class="fw-bold text-dark">🔒 Unlock Premium Staylink Hotel Services</h2>
                <p class="text-muted lead">Become a member and enjoy world-class facilities and services at The Staylink Hotel.</p>

                <!-- Features Section -->
                <div class="features-section mt-4">
                    <div class="feature-item" data-aos="fade-right">
                        <i class="bi bi-calendar-check feature-icon text-primary"></i>
                        <h5>📅 Instant Booking</h5>
                        <p class="text-muted">Reserve your stay effortlessly at The Staylink Hotel with just a click.</p>
                    </div>

                    <div class="feature-item" data-aos="fade-up">
                        <i class="bi bi-people-fill feature-icon text-success"></i>
                        <h5>👨‍👩‍👧‍👦 Join Our Elite Community</h5>
                        <p class="text-muted">Become part of a distinguished community of travelers and enjoy exclusive offers.</p>
                    </div>

                    <div class="feature-item" data-aos="fade-left">
                        <i class="bi bi-hotel feature-icon text-danger"></i>
                        <h5>🧳 Exceptional Stay Experience</h5>
                        <p class="text-muted">Experience unparalleled comfort and service during your stay with us.</p>
                    </div>
                </div>

                <!-- Call to Action -->
                <a href="login.php" class="btn btn-gradient btn-lg shadow-lg mt-4 px-5 py-3 login-btn" data-aos="zoom-in">
                    <i class="bi bi-arrow-right-circle"></i> Get Started Now
                </a>
            </div>
        </div>
    </div>

    <!-- Modern Styling -->
    <style>
        /* Lock Icon Animation */
        .lock-icon {
            font-size: 3.5rem;
            animation: pulse 1.5s infinite alternate ease-in-out;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }

        /* Access Card */
        .access-card {
            background: #1F1C2C;  /* fallback for old browsers */
            background: -webkit-linear-gradient(to bottom, #928DAB, #1F1C2C);  /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to bottom, #928DAB, #1F1C2C); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            border-radius: 15px;
            max-width: 800px;
            transition: all 0.3s ease-in-out;
        }

        .access-card:hover {
            box-shadow: 0px 12px 30px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px);
        }

        /* Feature Section */
        .features-section {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .feature-item {
            flex: 1;
            padding: 15px;
            text-align: center;
            transition: transform 0.3s ease-in-out;
        }

        .feature-item:hover {
            transform: scale(1.05);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        /* Modern Button */
        .btn-gradient {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            color: white;
            font-size: 1.2rem;
            font-weight: bold;
            border-radius: 50px;
            transition: all 0.3s ease-in-out;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #0056b3, #004494);
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.2);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .features-section {
                flex-direction: column;
            }
        }
    </style>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

<!-- Include necessary Bootstrap and jQuery JS files -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        AOS.init({ duration: 1000, once: true });
    });
</script>
