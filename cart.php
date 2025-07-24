<?php
include 'includes/header.php';
include 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: "error",
            title: "Oops!",
            text: "You must be logged in to view the cart.",
            footer: "<a href=\'login.php\'>Login Now</a>",
            showCloseButton: true,
            confirmButtonText: "Okay",
            customClass: {
                popup: "custom-popup"
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "login.php";
            }
        });
    </script>';
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT c.id as cart_id, m.name, m.price, c.quantity FROM cart c JOIN menu_items m ON c.menu_item_id = m.id WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.1/aos.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <title>Your Cart</title>
    <style>
        body {
            background-color: #121212; /* Dark background */
            color: #e0e0e0; /* Light text color */
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }

        .container {
            margin-top: 50px;
            border-radius: 15px; /* Rounded corners for the container */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5); /* Shadow effect */
            padding: 30px; /* Inner padding */
            background: linear-gradient(145deg, #1a1a1a, #1e1e1e); /* Gradient background */
        }

        h2 {
            color: #d32f2f; /* Striking title color */
            text-align: center;
            margin-bottom: 30px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .table {
            background-color: #2c2c2c; /* Dark table background */
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
        }

        th {
            background-color: #424242; /* Darker header color */
            color: #ffffff; /* White header text */
            text-align: center;
        }

        td {
            color: #e0e0e0; /* Light color for table data */
            text-align: center;
        }

        .btn-outline-danger {
            border-radius: 20px;
            transition: background-color 0.3s, color 0.3s;
        }

        .btn-outline-danger:hover {
            background-color: #f8d7da;
            color: #721c24;
        }

        .btn-success {
            border-radius: 25px;
            transition: background-color 0.3s ease, transform 0.3s;
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        .no-cart {
            text-align: center;
            color: #f44336; /* Alert color */
            font-weight: bold;
            margin-top: 20px;
        }

        .custom-popup {
            border-radius: 20px;
            background-color: #f8f9fa;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        /* New styles for a more distinct look */
        .cart-footer {
            border-top: 2px solid #e0e0e0;
            padding-top: 15px;
            margin-top: 20px;
            text-align: center;
        }

        .cart-footer h3 {
            color: #d32f2f; /* Striking total color */
        }

        .cart-message {
            color: #b0bec5; /* Muted message color */
        }
    </style>
</head>
<body>

<div class="container" data-aos="fade-up">
    <h2>Your Cart 🛒</h2>
    <div class="table-responsive">
        <table class="table table-hover table-borderless align-middle">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()):
                        $subtotal = $row['price'] * $row['quantity'];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td>Rs. <?php echo number_format($row['price'], 2); ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td>Rs. <?php echo number_format($subtotal, 2); ?></td>
                            <td>
                                <button class="btn btn-outline-danger btn-sm" onclick="removeFromCart(<?php echo $row['cart_id']; ?>)">Remove</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="no-cart">Your cart is empty!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="cart-footer">
        <h3>Total: Rs. <?php echo number_format($total, 2); ?></h3>
        <button class="btn btn-success px-5 py-2" onclick="proceedToCheckout()">Proceed to Checkout</button>
    </div>
    <div class="cart-message mt-4">
        <p>✨ Thank you for choosing us! Your cart is just a step away from happiness! ✨</p>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Include SweetAlert here -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.1/aos.js"></script>
<script>
    // Initialize AOS
    AOS.init({
        duration: 1000,
        once: true,
    });

    function removeFromCart(cartId) {
        $.ajax({
            url: 'processes/remove_from_cart.php',
            method: 'POST',
            data: { cart_id: cartId },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Item removed from cart! 🌟',
                    showConfirmButton: false,
                    timer: 1500
                });
                location.reload();
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Failed to remove item from cart. Please try again. 😢'
                });
            }
        });
    }

    function proceedToCheckout() {
        // Check if the total is 0
        var total = <?php echo $total; ?>; // Get the total from PHP

        if (total <= 0) {
            // Show an alert if the cart is empty
            Swal.fire({
                icon: 'warning',
                title: 'Cart is Empty!',
                text: 'You cannot proceed to checkout with an empty cart.',
                confirmButtonText: 'Okay'
            });
        } else {
            // Proceed to checkout if the cart has items
            window.location.href = 'checkout.php';
        }
    }
</script>

</body>
</html>
