<?php
include 'includes/header.php';
include 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to view your orders.";
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM orders WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders | The Staylink Hotel</title>
    
    <!-- Bootstrap and AOS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/orders.css">
    <style>
        /* assets/css/orders.css */

/* General Styling */
body {
    background-color: #0e0e0e;
    font-family: 'Merriweather', serif;
    color: #ccc;
}

#orders {
    background: radial-gradient(circle, #1e1e1e 0%, #0b0b0b 100%);
    padding: 100px 0;
}

h2 {
    font-family: 'Playfair Display', serif;
    color: #eaeaea;
    letter-spacing: 1.5px;
}

.table {
    background-color: rgba(34, 34, 34, 0.85);
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
}

.table th {
    color: #ffdd57;
    background-color: #333;
}

.table td {
    color: #ddd;
}

.table-hover tbody tr:hover {
    background-color: rgba(255, 221, 87, 0.1);
}

.no-orders {
    color: #ffcccb;
    font-size: 1.2rem;
    text-align: center;
    margin-top: 20px;
}

/* AOS Animations */
[data-aos="fade-down"] {
    opacity: 0;
    transform: translateY(-50px);
    transition-property: opacity, transform;
}

[data-aos="fade-down"].aos-animate {
    opacity: 1;
    transform: translateY(0);
}

[data-aos="fade-up"] {
    opacity: 0;
    transform: translateY(50px);
    transition-property: opacity, transform;
}

[data-aos="fade-up"].aos-animate {
    opacity: 1;
    transform: translateY(0);
}

/* Responsive */
@media (max-width: 768px) {
    h2 {
        font-size: 2.5rem;
    }

    .table {
        font-size: 0.9rem;
    }
}

    </style>
</head>
<body>
    <!-- Orders Section -->
    <section id="orders" class="py-5">
        <div class="container">
            <h2 class="text-uppercase display-4 text-center text-light mb-5" data-aos="fade-down">Your Orders</h2>

            <?php if ($result->num_rows > 0): ?>
                <table class="table table-dark table-hover table-striped" data-aos="fade-up">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Order Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td>Rs. <?php echo number_format($row['total_price'], 2); ?></td>
                                <td><?php echo $row['status']; ?></td>
                                <td><?php echo date('d-m-Y H:i:s', strtotime($row['created_at'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-light text-center" data-aos="fade-up">You have no orders yet!</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1200,
            once: true
        });
    </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
