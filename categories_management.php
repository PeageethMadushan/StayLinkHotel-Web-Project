<!-- admin_categories.php -->
<?php
include 'config.php';
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Categories</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Custom styles -->
    <style>
        /* Custom Styles for iOS-inspired layout */
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            background-color: #ced4da;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        table {
            border-radius: 10px;
            overflow: hidden;
        }

        thead {
            background-color: #007bff;
            color: white;
        }

        .modal-content {
            border-radius: 10px;
        }

        .btn-primary, .btn-warning, .btn-danger {
            border-radius: 25px;
        }

        .btn-close {
            background-color: transparent;
            color: #000;
            font-size: 1.2rem;
            border: none;
            cursor: pointer;
        }

        /* Button hover effects */
        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-warning:hover {
            background-color: #ffc107;
        }

        .btn-danger:hover {
            background-color: #dc3545;
        }
    </style>
    <!-- jQuery and Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4 text-center text-primary">Manage Categories</h2>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i class="fas fa-plus"></i> Add Category
    </button>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Category Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="categoryTable">
            <!-- Categories will be dynamically loaded here -->
        </tbody>
    </table>
</div>

<!-- Modals (Add and Edit) -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="categoryName" name="name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCategoryForm">
                    <input type="hidden" id="editCategoryId" name="id">
                    <div class="mb-3">
                        <label for="editCategoryName" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="editCategoryName" name="name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Document Ready
$(document).ready(function() {
    loadCategories();

    function loadCategories() {
        $.ajax({
            url: 'processes/fetch_categories.php',
            method: 'GET',
            success: function(response) {
                $('#categoryTable').html(response);
            }
        });
    }

    // Add category form submission
    $('#addCategoryForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'processes/add_category.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#addCategoryModal').modal('hide');
                $('#addCategoryForm')[0].reset(); // Clear the form
                loadCategories();
            }
        });
    });

    // Edit category button click
    $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        $('#editCategoryId').val(id);
        $('#editCategoryName').val(name);
        $('#editCategoryModal').modal('show');
    });

    // Edit category form submission
    $('#editCategoryForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'processes/edit_category.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#editCategoryModal').modal('hide');
                $('#editCategoryForm')[0].reset(); // Clear the form
                loadCategories();
            }
        });
    });

    // Delete category button click
    $(document).on('click', '.delete-btn', function() {
        if (confirm('Are you sure you want to delete this category?')) {
            const id = $(this).data('id');
            $.ajax({
                url: 'processes/delete_category.php',
                method: 'POST',
                data: { id: id },
                success: function(response) {
                    loadCategories();
                }
            });
        }
    });
});
</script>

</body>
</html>
