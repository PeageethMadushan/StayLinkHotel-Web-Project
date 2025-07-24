<?php
session_start();
include 'config.php';

// Check user role
if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Fetch tables
$sql = "SELECT * FROM tables";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/css/select2.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Link to custom styles -->

    <style>
/* Base styling */
body {
    background-color: #121212; /* Dark minimalistic background */
    color: #f5f5f5;
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    min-height: 100vh;
}

.container {
    background-color: #1e1e1e; /* Dark container background */
    color: #ffffff;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
}

h3 {
    font-weight: 600;
    color: #f1f1f1;
    text-align: center;
}

.table {
    background-color: #2c2c2c;
    border-radius: 10px;
    overflow: hidden;
}

.table th {
    background-color: #1f1f1f;
    color: #f5f5f5;
    text-transform: uppercase;
}

.table td, .table th {
    border-color: #3e3e3e;
    color: #fff;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: #242424; /* Striped rows */
}

.table-hover tbody tr:hover {
    background-color: #3b3b3b; /* Hover effect */
    transition: background-color 0.3s;
}

.btn-primary, .btn-warning, .btn-danger {
    border-radius: 25px;
    transition: all 0.3s ease;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

.btn-warning {
    background-color: #ffb900;
    border-color: #ffb900;
}

.btn-warning:hover {
    background-color: #e6a100;
    border-color: #e6a100;
}

.btn-danger {
    background-color: #ff4d4d;
    border-color: #ff4d4d;
}

.btn-danger:hover {
    background-color: #e60000;
    border-color: #e60000;
}

/* Modal Styling */
.modal-content {
    background-color: #1e1e1e;
    color: #f5f5f5;
    border-radius: 15px;
    border: none;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
}

.modal-header {
    background-color: #007bff;
    border-bottom: none;
    color: #ffffff;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
}

.modal-footer {
    border-top: none;
}

.modal-header .close {
    color: #ffffff;
    opacity: 0.8;
}

.modal-header .close:hover {
    color: #ffffff;
    opacity: 1;
}

.form-control {
    background-color: #2c2c2c;
    color: #ffffff;
    border: 1px solid #3e3e3e;
    border-radius: 8px;
}

.form-control:focus {
    background-color: #3b3b3b;
    border-color: #5a5a5a;
    box-shadow: none;
    color: #fff;
}

/* Button focus and hover states */
button:focus, .btn:focus {
    outline: none;
    box-shadow: none;
    transform: scale(1.02); /* Button subtle scale effect */
}

.modal-footer .btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    border-radius: 25px;
}

.modal-footer .btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

/* Enhancing UI for small screen sizes */
@media (max-width: 768px) {
    .container {
        padding: 15px;
    }

    h3 {
        font-size: 1.5rem;
    }
}


    </style>
</head>
<body>
<div class="container mt-5">
    <h3 class="text-center">Table Management</h3>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addTableModal">Add Table</button>

    <!-- Table Management Table -->
    <table class="table table-striped table-hover">
        <thead class="thead-light">
            <tr>
                <th>Table Number</th>
                <th>Capacity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['table_number']); ?></td>
                    <td><?= htmlspecialchars($row['capacity']); ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning editTable" data-id="<?= $row['id']; ?>">Edit</button>
                        <button class="btn btn-sm btn-danger deleteTable" data-id="<?= $row['id']; ?>">Delete</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Add Table Modal -->
<div class="modal fade" id="addTableModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Table</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addTableForm">
                    <div class="form-group">
                        <label for="tableNumber">Table Number</label>
                        <input type="number" class="form-control" id="tableNumber" name="table_number" required>
                    </div>
                    <div class="form-group">
                        <label for="capacity">Capacity</label>
                        <input type="number" class="form-control" id="capacity" name="capacity" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Table</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Table Modal -->
<div class="modal fade" id="editTableModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Table</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editTableForm">
                    <input type="hidden" id="editTableId" name="id">
                    <div class="form-group">
                        <label for="editTableNumber">Table Number</label>
                        <input type="number" class="form-control" id="editTableNumber" name="table_number" required>
                    </div>
                    <div class="form-group">
                        <label for="editCapacity">Capacity</label>
                        <input type="number" class="form-control" id="editCapacity" name="capacity" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Table</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/js/select2.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    // Load tables when the page loads
    loadTables();

    // Function to load tables dynamically
    function loadTables() {
        $.get('processes/get_all_tables.php', function(data) {
            const tables = JSON.parse(data);
            
            if (tables.length > 0) {
                let tableRows = '';
                tables.forEach(function(table) {
                    tableRows += `
                        <tr>
                            <td>${table.table_number}</td>
                            <td>${table.capacity}</td>
                            <td>
                                <button class="btn btn-sm btn-warning editTable" data-id="${table.id}">Edit</button>
                                <button class="btn btn-sm btn-danger deleteTable" data-id="${table.id}">Delete</button>
                            </td>
                        </tr>
                    `;
                });
                $('table tbody').html(tableRows);
                
                // Re-bind the edit and delete button actions
                bindTableActions();
            } else {
                $('table tbody').html('<tr><td colspan="3">No tables found.</td></tr>');
            }
        }).fail(function() {
            alert('Error fetching table data.');
        });
    }

    // Re-bind edit and delete button actions after loading data
    function bindTableActions() {
        // Handle Edit Table button click
        $('.editTable').click(function() {
            var id = $(this).data('id');
            $.get('processes/get_table.php', { id: id }, function(data) {
                var table = JSON.parse(data);
                if (table.error) {
                    alert(table.error);
                } else {
                    $('#editTableId').val(table.id);
                    $('#editTableNumber').val(table.table_number);
                    $('#editCapacity').val(table.capacity);
                    $('#editTableModal').modal('show');
                }
            }).fail(function() {
                alert('Error fetching table data.');
            });
        });

        // Handle Delete Table button click
        $('.deleteTable').click(function() {
            if (confirm('Are you sure you want to delete this table?')) {
                var id = $(this).data('id');
                $.post('processes/delete_table.php', { id: id }, function(response) {
                    alert(response);
                    loadTables(); // Reload the table data after deletion
                }).fail(function() {
                    alert('Error deleting table.');
                });
            }
        });
    }

    // Handle Add Table form submission
    $('#addTableForm').on('submit', function(e) {
        e.preventDefault();
        $.post('processes/add_table.php', $(this).serialize(), function(response) {
            alert(response);
            $('#addTableModal').modal('hide');
            loadTables(); // Reload the table data after adding
        }).fail(function() {
            alert('Error adding table.');
        });
    });

    // Handle Edit Table form submission
    $('#editTableForm').on('submit', function(e) {
        e.preventDefault();
        $.post('processes/edit_table.php', $(this).serialize(), function(response) {
            alert(response);
            $('#editTableModal').modal('hide');
            loadTables(); // Reload the table data after editing
        }).fail(function() {
            alert('Error updating table.');
        });
    });
});

</script>
</body>
</html>
