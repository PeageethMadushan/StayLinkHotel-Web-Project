<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - The Staylink Hotel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(145deg, #0c0c0c, #1f1f1f);
            color: #e0e0e0;
            font-family: 'Poppins', sans-serif;
        }

        /* Navbar */
        .navbar {
            background-color: #181818;
            border-bottom: 2px solid #444;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 26px;
            color: #e0e0e0;
        }
        .nav-link {
            color: #bbb;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: #007bff;
        }

        /* Sidebar */
        .sidebar {
            background-color: #151515;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.6);
            min-height: 100vh;
        }
        .sidebar .list-group-item {
            background-color: transparent;
            color: #ccc;
            border: none;
            padding: 20px;
            font-size: 18px;
            transition: all 0.3s ease;
        }
        .sidebar .list-group-item:hover,
        .sidebar .list-group-item.active {
            background-color: #007bff;
            color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 123, 255, 0.5);
        }

        /* Content Area */
        .content-area {
            background-color: #181818;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 0px 30px rgba(0, 0, 0, 0.5);
            color: #ccc;
        }

        /* Headings */
        h3 {
            font-weight: 600;
            color: #007bff;
            margin-bottom: 20px;
        }

        /* Buttons */
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid mt-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="sidebar">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action active" id="userManagement">
                            <i class="fas fa-users"></i> User Management
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" id="menuManagement">
                            <i class="fas fa-utensils"></i> Menu Management
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" id="reservationManagement">
                            <i class="fas fa-calendar-alt"></i> Reservation Management
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" id="tableManagement">
                            <i class="fas fa-chair"></i> Table Management
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" id="orderManagement">
                            <i class="fas fa-receipt"></i> Order Management
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" id="categoryManagement">
                            <i class="fas fa-list"></i> Category Management
                        </a>
                        <!-- New Navigation Links -->
                        <a href="#" class="list-group-item list-group-item-action" id="roomManagement">
                            <i class="fas fa-bed"></i> Manage Rooms
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" id="roomBookingManagement">
                            <i class="fas fa-calendar-check"></i> Manage Room Bookings
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" id="vehicleManagement">
                            <i class="fas fa-car"></i> Manage Vehicles
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" id="vehicleRentalManagement">
                            <i class="fas fa-car-side"></i> Manage Rentals
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" id="eventManagement">
                            <i class="fas fa-calendar-day"></i> Manage Events
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" id="eventBookingManagement">
                            <i class="fas fa-calendar-check"></i> Manage Event Bookings
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-md-9">
                <div class="content-area" id="dashboardContent">
                    <!-- Content loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
       $(document).ready(function() {
    // Load default content
    loadContent('user_management.php');

    // Handle sidebar item clicks
    $('#userManagement').click(function() {
        loadContent('user_management.php');
    });
    $('#menuManagement').click(function() {
        loadContent('menu_management.php');
    });
    $('#reservationManagement').click(function() {
        loadContent('reservation_management.php');
    });
    $('#tableManagement').click(function() {
        loadContent('table_management.php');
    });
    $('#orderManagement').click(function() {
        loadContent('order_management.php');
    });
    $('#categoryManagement').click(function() {
        loadContent('categories_management.php');
    });
    // Handle new management sections
    $('#roomManagement').click(function() {
        loadContent('room_management.php');
    });
    $('#roomBookingManagement').click(function() {
        loadContent('room_booking_management.php');
    });
    $('#vehicleManagement').click(function() {
        loadContent('vehicle_management.php');
    });
    $('#vehicleRentalManagement').click(function() {
        loadContent('vehicle_rental_management.php');
    });
    $('#eventManagement').click(function() {
        loadContent('event_management.php');
    });
    $('#eventBookingManagement').click(function() {
        loadContent('event_booking_management.php');
    });

    // Attach event listeners using delegation
    function loadContent(page) {
        $('#dashboardContent').fadeOut(200, function() {
            $('#dashboardContent').load(page, function() {
                $('#dashboardContent').fadeIn(200);
                
                // Attach event listeners for dynamically loaded content
                $('body').on('click', '.editReservation', function() {
                    var reservationId = $(this).data('id');
                    // Call function to load the reservation details into the edit modal
                    loadReservationDetails(reservationId);
                    $('#editReservationModal').modal('show');
                });

                $('body').on('click', '.deleteReservation', function() {
                    var reservationId = $(this).data('id');
                    // Call function to delete the reservation (add confirmation logic)
                    deleteReservation(reservationId);
                });
            });
        });
    }
    
    function loadReservationDetails(id) {
        // AJAX call to fetch reservation details by ID
        $.ajax({
            url: 'get_reservation_details.php', // Ensure this script returns the reservation details
            type: 'GET',
            data: { id: id },
            success: function(data) {
                // Populate modal fields with fetched data
                $('#editReservationId').val(data.id);
                $('#editUserName').val(data.user_id); // Assuming you're returning user_id
                $('#editTableNumber').val(data.table_number); // Same for table_number
                $('#editReservationTime').val(data.reservation_time);
            },
            error: function(error) {
                console.error('Failed to load reservation details:', error);
            }
        });
    }
    
    function deleteReservation(id) {
        if (confirm('Are you sure you want to delete this reservation?')) {
            // AJAX call to delete the reservation
            $.ajax({
                url: 'delete_reservation.php',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    // Handle successful deletion (reload or remove the row)
                    alert('Reservation deleted successfully');
                    loadContent('reservation_management.php'); // Reload the content
                },
                error: function(error) {
                    console.error('Failed to delete reservation:', error);
                }
            });
        }
    }
});
    </script>
</body>
</html>
