<?php
session_start();

// Check if user is logged in and has the 'staff' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - The Staylink Hotel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Custom CSS -->
    <style>
        body {
            background-color: #f4f4f9;
            font-family: 'Roboto', sans-serif;
        }
        .navbar {
            background-color: #343a40; /* Dark background for navbar */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #ffffff !important; /* Ensure brand name is white */
        }
        .navbar-nav .nav-link {
            color: #ffffff !important; /* Ensure nav links are white */
            font-size: 1rem;
            font-weight: 500;
        }
        .navbar-nav .nav-link:hover {
            color: #c1121f !important; /* Light gray for hover effect */
            text-decoration: none;
        }
        .btn-logout {
            background-color: transparent; /* Remove background */
            border: none;
            padding: 0;
            font-size: 1rem;
            font-weight: 500;
            color: #ffffff; /* Ensure text color is white */
        }
        .btn-logout:hover {
            color: #e0e0e0; /* Light gray color on hover */
            text-decoration: none;
        }
        .btn-logout i {
            color: #c1121f; /* Ensure icon color is white */
        }
        .btn-logout:hover i {
            color: #c1121f; /* Light gray color for icon on hover */
        }
        .list-group-item {
            border: none;
            border-radius: 0;
            transition: background-color 0.3s, color 0.3s;
        }
        .list-group-item:hover, .list-group-item.active {
            background-color: #007bff;
            color: #ffffff;
            cursor: pointer;
        }
        #dashboardContent {
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: opacity 0.3s ease-in-out;
        }
        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease-in-out;
        }
        .card:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">Staff Dashboard</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link btn-logout" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action active" id="reservationManagement">Reservation Management</a>
                    <a href="#" class="list-group-item list-group-item-action" id="orderManagement">Order Management</a>
                </div>
            </div>
            <div class="col-md-9">
                <div id="dashboardContent">
                    <!-- Content will be loaded here dynamically using AJAX -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Load the default content
            loadContent('reservation_management.php');

            // Handle menu item clicks
            $('#reservationManagement').click(function() {
                loadContent('reservation_management.php');
                $('.list-group-item').removeClass('active');
                $(this).addClass('active');
            });
            $('#orderManagement').click(function() {
                loadContent('order_management.php');
                $('.list-group-item').removeClass('active');
                $(this).addClass('active');
            });

            function loadContent(page) {
                $('#dashboardContent').fadeOut(200, function() {
                    $('#dashboardContent').load(page, function() {
                        $('#dashboardContent').fadeIn(200);
                    });
                });
            }
        });
    </script>
</body>
</html>
