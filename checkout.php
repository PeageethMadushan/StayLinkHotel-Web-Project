<?php
include 'includes/header.php';
include 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to proceed to checkout.";
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT c.menu_item_id, m.price, c.quantity FROM cart c JOIN menu_items m ON c.menu_item_id = m.id WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
$orderDetails = [];

while ($row = $result->fetch_assoc()) {
    $subtotal = $row['price'] * $row['quantity'];
    $total += $subtotal;
    $orderDetails[] = $row;
}

// Ensure the order cannot be processed if the total is zero
if ($total <= 0) {
    echo "<script>alert('Your cart is empty. Please add items to your cart before proceeding to checkout.');</script>";
    echo "<script>window.location.href = 'cart.php';</script>";
    exit;
}

// Insert order into orders table
$query = "INSERT INTO orders (user_id, total_price) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("id", $user_id, $total);
$stmt->execute();
$order_id = $stmt->insert_id;

// Insert order details into order_details table
foreach ($orderDetails as $detail) {
    $query = "INSERT INTO order_details (order_id, menu_item_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiid", $order_id, $detail['menu_item_id'], $detail['quantity'], $detail['price']);
    $stmt->execute();
}

// Clear user's cart
$query = "DELETE FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.1/aos.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <title>Checkout Confirmation</title>
    <style>
        body {
            background: radial-gradient(circle, #0b0c10, #1f2833);
            font-family: 'Montserrat', sans-serif;
            color: #c5c6c7;
        }
    </style>
</head>
<body>

<div class="container mx-auto my-20 px-4" data-aos="fade-up">
    <div class="bg-gradient-to-r from-[#45a29e] to-[#66fcf1] rounded-lg shadow-2xl p-8 transition-transform duration-300 hover:scale-105">
        <h2 class="text-white text-4xl font-bold text-center mb-4">Order Confirmation 🎉</h2>
        <p class="text-center mb-6">Thank you! Your order has been successfully placed.</p>
        <h3 class="text-white text-2xl text-center">Total Amount: Rs. <?php echo number_format($total, 2); ?></h3>
        <div class="text-center mt-6">
            <a href="orders.php" class="bg-[#1f2833] hover:bg-[#0b0c10] text-white font-semibold py-2 px-6 rounded-full transition duration-300 transform hover:translate-y-1 shadow-lg">
                Check Your Orders
            </a>
        </div>
    </div>

    <div class="mt-10 text-center">
        <p class="text-[#c5c6c7]">You will be redirected to your orders page in <span id="timer">5</span> seconds...</p>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.1/aos.js"></script>
<script>
    // Initialize AOS
    AOS.init({
        duration: 1000,
        once: true,
    });

    let countdown = 5;
    const timerElement = document.getElementById('timer');
    const interval = setInterval(() => {
        countdown--;
        timerElement.innerText = countdown;
        if (countdown <= 0) {
            clearInterval(interval);
            window.location.href = 'orders.php';
        }
    }, 1000); // Update countdown every second
</script>

<?php include 'includes/footer.php'; ?>
</body>
</html>
