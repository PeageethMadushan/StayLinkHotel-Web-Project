<?php
session_start();
include 'config.php';

// Ensure the user is either an admin or staff
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff') {
    header('Location: login.php');
    exit();
}

// Fetch all reservations with user details
$sql = "SELECT r.*, u.name as user_name, t.capacity FROM reservations r 
        JOIN users u ON r.user_id = u.id
        JOIN tables t ON r.table_number = t.table_number";
$result = $conn->query($sql);

// Fetch all tables with capacities
$tableSql = "SELECT * FROM tables";
$tableResult = $conn->query($tableSql);
$tables = [];
while ($row = $tableResult->fetch_assoc()) {
    $tables[] = $row;
}

// Fetch users with the role of 'customer'
$userSql = "SELECT id, name FROM users WHERE role = 'customer'";
$userResult = $conn->query($userSql);
$users = [];
while ($row = $userResult->fetch_assoc()) {
    $users[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Management</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Your custom styles -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
    <style>
/* General Styles */
body {
    background-color: #121212; /* Dark background */
    color: #f1f1f1; /* Light text */
    font-family: 'Poppins', sans-serif; /* Sleek typography */
    font-size: 16px;
    line-height: 1.6;
    margin: 0;
    padding: 0;
}

.container {
    background-color: #1e1e1e; /* Darker container background */
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    transition: all 0.3s ease;
}

.container:hover {
    transform: scale(1.02); /* Slight hover effect */
}

h3 {
    color: #d79f7a; /* Elegant warm color */
    text-transform: uppercase;
    font-weight: bold;
}

.btn-primary {
    background-color: #d79f7a;
    border: none;
    border-radius: 50px;
    padding: 10px 20px;
    transition: all 0.3s ease;
    font-weight: bold;
    color: #121212;
}

.btn-primary:hover {
    background-color: #e6b295; /* Lighter on hover */
    transform: translateY(-5px); /* Subtle lift */
}

.btn-warning, .btn-danger {
    border-radius: 20px;
}

.btn-warning:hover, .btn-danger:hover {
    transform: translateY(-3px);
}

/* Table Styling */
.table {
    border-radius: 12px;
    background-color: #232323; /* Darker table background */
}

.table th {
    background-color: #d79f7a; /* Warm color for table headers */
    color: white;
    text-align: center;
    font-weight: bold;
}

.table td {
    color: #f1f1f1;
    text-align: center;
    padding: 15px;
    font-size: 0.95rem;
}

/* Modal Styling */
.modal-content {
    background-color: #1e1e1e;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);
    color: #f1f1f1;
}

.modal-header {
    background-color: #d79f7a;
    border-bottom: none;
    color: #fff;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #fff;
    text-transform: uppercase;
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    border-top: none;
}

.close {
    color: #fff;
    opacity: 0.8;
}

.close:hover {
    opacity: 1;
}

.btn-primary:disabled {
    background-color: #4a4a4a;
}

/* Form Elements */
.form-group label {
    font-weight: 600;
    color: #d79f7a;
}

.form-control {
    background-color: #232323;
    border: 1px solid #d79f7a;
    border-radius: 10px;
    color: #f1f1f1;
    padding: 12px;
}

.form-control:focus {
    background-color: #fff;
    box-shadow: 0 0 10px rgba(215, 159, 122, 0.4);
    border-color: #d79f7a;
}

.select2-container--default .select2-selection--single {
    background-color: #232323;
    border: 1px solid #d79f7a;
    border-radius: 10px;
    height: 40px;
    color: #f1f1f1;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    padding-left: 15px;
    line-height: 40px;
}

/* Button States */
button[type="submit"] {
    background-color: #d79f7a;
    border: none;
    border-radius: 20px;
    padding: 10px 20px;
    transition: all 0.3s ease;
    font-weight: 600;
    color: #121212;
}

button[type="submit"]:hover {
    background-color: #e6b295;
    transform: translateY(-3px);
}

/* Responsive Styling */
@media (max-width: 768px) {
    .container {
        padding: 20px;
    }

    .table th, .table td {
        font-size: 0.85rem;
    }

    h3 {
        font-size: 1.25rem;
    }
}

    </style>
</head>
<body>
<div class="container mt-5">
    <h3 class="text-center mb-4">Reservation Management</h3>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addReservationModal">Add Reservation</button>

    <!-- Reservations Table -->
    <table class="table table-striped table-hover">
        <thead class="thead-light">
            <tr>
                <th>User Name</th>
                <th>Table Number</th>
                <th>Capacity</th>
                <th>Reservation Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['user_name']); ?></td>
                    <td><?= htmlspecialchars($row['table_number']); ?></td>
                    <td><?= htmlspecialchars($row['capacity']); ?></td>
                    <td><?= htmlspecialchars($row['reservation_time']); ?></td>
                    <td><?= htmlspecialchars($row['status']); ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm editReservation" data-id="<?= $row['id']; ?>">Edit</button>
                        <button class="btn btn-danger btn-sm deleteReservation" data-id="<?= $row['id']; ?>">Delete</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Add Reservation Modal -->
<div class="modal fade" id="addReservationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Reservation</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addReservationForm">
                    <div class="form-group">
                        <label for="userName">User Name</label>
                        <select class="form-control select2" id="userName" name="user_name" required>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['id']; ?>"><?= htmlspecialchars($user['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tableNumber">Table Number</label>
                        <select class="form-control" id="tableNumber" name="table_number" required>
                            <?php foreach ($tables as $table): ?>
                                <option value="<?= $table['table_number']; ?>"><?= $table['table_number']; ?> (Capacity: <?= $table['capacity']; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="reservationTime">Reservation Time</label>
                        <input type="datetime-local" class="form-control" id="reservationTime" name="reservation_time" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Reservation</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Reservation Modal -->
<div class="modal fade" id="editReservationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Edit Reservation</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editReservationForm">
                    <input type="hidden" id="editReservationId" name="id">
                    <div class="form-group">
                        <label for="editUserName">User Name</label>
                        <select class="form-control select2" id="editUserName" name="user_name" required>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['id']; ?>"><?= htmlspecialchars($user['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editTableNumber">Table Number</label>
                        <select class="form-control" id="editTableNumber" name="table_number" required>
                            <?php foreach ($tables as $table): ?>
                                <option value="<?= $table['table_number']; ?>"><?= $table['table_number']; ?> (Capacity: <?= $table['capacity']; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editReservationTime">Reservation Time</label>
                        <input type="datetime-local" class="form-control" id="editReservationTime" name="reservation_time" required>
                    </div>
                    <div class="form-group">
                        <label for="editStatus">Status</label>
                        <select class="form-control" id="editStatus" name="status" required>
                            <option value="Pending">Pending</option>
                            <option value="Confirmed">Confirmed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-warning">Update Reservation</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        // Initialize Select2 for enhanced dropdowns
        $('.select2').select2({
            placeholder: 'Select an option',
            allowClear: true
        });

        // Handle Add Reservation form submission
        $('#addReservationForm').on('submit', function (e) {
            e.preventDefault();
            $('#addReservationForm button[type="submit"]').prop('disabled', true).text('Adding...');
            $.post('processes/add_reservation.php', $(this).serialize(), function (response) {
                alert(response);
                $('#addReservationModal').modal('hide');
                $('#addReservationForm')[0].reset();
                $('#addReservationForm button[type="submit"]').prop('disabled', false).text('Add Reservation');
                fetchReservations(); // Refresh reservations without page reload
            }).fail(function () {
                alert('Error adding reservation.');
                $('#addReservationForm button[type="submit"]').prop('disabled', false).text('Add Reservation');
            });
        });

        // Handle the Edit button click using event delegation
        $(document).on('click', '.editReservation', function () {
            var id = $(this).data('id');
            $.get('processes/get_reservation.php', { id: id }, function (data) {
                const reservation = JSON.parse(data);
                $('#editReservationId').val(reservation.id);
                $('#editUserName').val(reservation.user_id).trigger('change');
                $('#editTableNumber').val(reservation.table_number);
                $('#editReservationTime').val(reservation.reservation_time);
                $('#editStatus').val(reservation.status);
                $('#editReservationModal').modal('show');
            });
        });

        // Handle Edit Reservation form submission
        $('#editReservationForm').on('submit', function (e) {
            e.preventDefault();
            $('#editReservationForm button[type="submit"]').prop('disabled', true).text('Updating...');
            $.post('processes/edit_reservation.php', $(this).serialize(), function (response) {
                alert(response);
                $('#editReservationModal').modal('hide');
                $('#editReservationForm')[0].reset();
                $('#editReservationForm button[type="submit"]').prop('disabled', false).text('Update Reservation');
                fetchReservations(); // Refresh reservations without page reload
            }).fail(function () {
                alert('Error updating reservation.');
                $('#editReservationForm button[type="submit"]').prop('disabled', false).text('Update Reservation');
            });
        });

        // Handle Delete button click using event delegation
        $(document).on('click', '.deleteReservation', function () {
            if (confirm('Are you sure you want to delete this reservation?')) {
                var id = $(this).data('id');
                $.post('processes/delete_reservation.php', { id: id }, function (response) {
                    alert(response);
                    fetchReservations(); // Refresh reservations without page reload
                }).fail(function () {
                    alert('Error deleting reservation.');
                });
            }
        });

        // Function to fetch reservations without reloading the page
        function fetchReservations() {
            $.get('processes/fetch_reservations.php', function (data) {
                $('tbody').html(data);
            });
        }

        // Initial fetch on page load
        fetchReservations();
    });
</script>

</body>
</html>
