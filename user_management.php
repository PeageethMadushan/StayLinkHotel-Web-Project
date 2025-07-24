<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'config.php';

// Ensure user is an admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Fetch existing users
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - The Staylink Hotel</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Prata&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1a1a1a; /* Dark background */
            color: #f0f0f0; /* Light text color */
            overflow-x: hidden;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 30px;
            background: rgba(30, 30, 30, 0.9); /* Semi-transparent background */
            border-radius: 15px; /* Rounded corners */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.8);
        }
        h3 {
            margin-top: 20px;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
        }
        .form-control, .custom-select {
            background-color: #333; /* Dark background for inputs */
            color: #f0f0f0; /* Light text color */
            border: 1px solid #555; /* Input border color */
            border-radius: 10px;
        }
        .form-control:focus, .custom-select:focus {
            border-color: #d65db1; /* Focus border color */
            box-shadow: 0 0 8px rgba(214, 93, 177, 0.5);
        }
        .btn-primary {
            background-color: #d65db1; /* Unique button color */
            border-radius: 10px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #ff6f61; /* Hover color */
            transform: scale(1.05);
        }
        table {
            background-color: #292929; /* Dark table background */
            border-radius: 15px;
            margin-top: 20px;
            overflow: hidden; /* Prevent overflow from rounded corners */
        }
        th, td {
            padding: 15px;
            text-align: center;
            transition: background-color 0.3s ease;
            color: #fff;
        }
        th {
            background-color: #444; /* Dark header background */
            color: #fff;
        }
        td:hover {
            background-color: #555; /* Unique hover effect */
        }
        .btn-warning, .btn-danger {
            border-radius: 10px;
        }
        #editUserContainer {
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        #editUserContainer.show {
            opacity: 1;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Add New User Form -->
    <h3>Add New User</h3>
    <form id="addUserForm">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <select class="custom-select" id="role" name="role" required>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
                <option value="customer">Customer</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add User</button>
    </form>

    <div id="editUserContainer"></div>

    <!-- User Management Table -->
    <h3 class="mt-5">User Management</h3>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td><?= ucfirst(htmlspecialchars($row['role'])); ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning editUser" data-id="<?= htmlspecialchars($row['id']); ?>">Edit</button>
                        <button class="btn btn-sm btn-danger deleteUser" data-id="<?= htmlspecialchars($row['id']); ?>">Delete</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
    // Load edit user form
    $(document).on('click', '.editUser', function() {
        var userId = $(this).data('id');
        $.get('processes/edit_user_form.php', { id: userId }, function(data) {
            $('#editUserContainer').html(data).addClass('show');
        });
    });

    // Handle user deletion
    $(document).on('click', '.deleteUser', function() {
        if (confirm('Are you sure you want to delete this user?')) {
            var userId = $(this).data('id');
            $.post('processes/delete_user.php', { id: userId }, function(response) {
                alert(response);
                location.reload();
            });
        }
    });

    // Handle add user form submission
    $(document).on('submit', '#addUserForm', function(event) {
        event.preventDefault();
        $.ajax({
            url: 'processes/add_user.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                alert(response);
                $('#addUserForm')[0].reset();
                location.reload();
            },
            error: function() {
                alert('Error adding user.');
            }
        });
    });

    // Handle edit user form submission
    $(document).on('submit', '#editUserForm', function(event) {
        event.preventDefault();
        $.ajax({
            url: 'processes/edit_user.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                alert(response);
                $('#editUserContainer').html('').removeClass('show'); // Clear the edit user form
                location.reload();
            },
            error: function() {
                alert('Error updating user.');
            }
        });
    });
</script>
</body>
</html>
