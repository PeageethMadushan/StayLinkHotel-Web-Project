<?php
include 'config.php';

// Fetch menu items with category names
$sql = "SELECT menu_items.*, categories.name AS category_name 
        FROM menu_items
        LEFT JOIN categories ON menu_items.category_id = categories.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Management - The Staylink Hotel</title>
    
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #121212; /* Dark background */
            color: #ffffff; /* Light text */
        }

        .container {
            padding: 2rem;
            background: #1e1e1e; /* Darker container */
            border-radius: 15px; /* Slightly rounded edges */
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3); /* Softer shadow for depth */
            height: 400px;
        }

        h3 {
            font-weight: 600;
            color: #ffffff; /* White for headings */
            margin-bottom: 1.5rem;
            position: relative;
        }

        h3::after {
            content: '';
            width: 80px;
            height: 4px;
            background-color: #7c4dff; /* Accent color */
            position: absolute;
            left: 0;
            bottom: -10px;
            border-radius: 2px; /* Rounded underline */
        }

        .form-control, .form-select {
            border-radius: 10px; /* Rounded inputs */
            border: 1px solid #444; /* Dark border */
            background-color: #333; /* Dark background for inputs */
            color: #ffffff; /* White text for inputs */
        }

        .form-control:focus, .form-select:focus {
            border-color: #7c4dff;
            box-shadow: 0 0 5px rgba(124, 77, 255, 0.3); /* Soft focus shadow */
        }

        .card {
            background-color: #1e1e1e; /* Dark card background */
            border: 1px solid #444; /* Darker border for cards */
            border-radius: 10px; /* Rounded corners for cards */
            margin-bottom: 1.5rem; /* Spacing between cards */
            transition: transform 0.3s ease, box-shadow 0.3s ease; /* Smooth transition */
        }

        .card:hover {
            transform: translateY(-4px); /* Subtle lift effect on hover */
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.5); /* Stronger shadow on hover */
        }

        .card img {
            border-radius: 10px 10px 0 0; /* Rounded top corners for images */
        }

        .card-body {
            padding: 1rem; /* Inner padding for card body */
        }

        .btn {
            border-radius: 10px; /* Rounded buttons */
            padding: 10px 20px; /* Increased padding */
            transition: all 0.3s ease-in-out;
            font-weight: 600; /* Medium button text */
        }

        .btn-primary {
            background-color: #7c4dff; /* Primary color */
            border: none; /* Remove border */
        }

        .btn-primary:hover {
            background-color: #5e35b1; /* Darker primary on hover */
            transform: translateY(-2px); /* Subtle lift effect */
        }

        .btn-warning {
            background-color: #ffca28; /* Warm yellow */
            border: none;
        }

        .btn-warning:hover {
            background-color: #ffb300;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: #d32f2f; /* Red for danger */
            border: none;
        }

        .btn-danger:hover {
            background-color: #c62828;
            transform: translateY(-2px);
        }

        #editMenuContainer {
            padding-top: 2rem;
        }

        /* Animations */
        .fadeIn {
            animation: fadeIn 0.5s;
        }

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

<div class="container fadeIn">
    <h3>Add New Menu Item</h3>
    <form id="addMenuForm" enctype="multipart/form-data">
        <div class="form-group mb-3">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter menu item name" required>
        </div>
        <div class="form-group mb-3">
            <label for="description">Description:</label>
            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter description" required></textarea>
        </div>
        <div class="form-group mb-3">
            <label for="price">Price:</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="Enter price" required>
        </div>
        <div class="form-group mb-3">
            <label for="category">Category:</label>
            <select class="form-select" id="category" name="category" required>
                <?php
                // Fetch categories for dropdown
                $categoryQuery = "SELECT id, name FROM categories";
                $categoryResult = $conn->query($categoryQuery);
                while ($row = $categoryResult->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($row['id']); ?>"><?= htmlspecialchars($row['name']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="image">Image:</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Menu Item</button>
    </form>

    <h3 class="mt-5">Menu Items</h3>
    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-4">
                <div class="card">
                    <img src="<?= htmlspecialchars($row['image']); ?>" alt="Menu Item Image" class="card-img-top" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($row['name']); ?></h5>
                        <p class="card-text"><?= htmlspecialchars($row['description']); ?></p>
                        <p class="card-text"><strong>Price: $<?= htmlspecialchars($row['price']); ?></strong></p>
                        <p class="card-text"><em>Category: <?= htmlspecialchars($row['category_name']); ?></em></p>
                        <button class="btn btn-warning editMenuItem" data-id="<?= $row['id']; ?>">Edit</button>
                        <button class="btn btn-danger deleteMenuItem" data-id="<?= $row['id']; ?>">Delete</button>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <div id="editMenuContainer"></div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

<script>
    // Handle add menu item form submission
    $('#addMenuForm').submit(function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: 'processes/add_menu_item.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                // Parse the JSON response
                const data = JSON.parse(response);
                if (data.success) {
                    // Create new card for the added menu item
                    var newItem = `
                        <div class="col-md-4">
                            <div class="card">
                                <img src="${data.image}" alt="Menu Item Image" class="card-img-top" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">${data.name}</h5>
                                    <p class="card-text">${data.description}</p>
                                    <p class="card-text"><strong>Price: $${data.price}</strong></p>
                                    <p class="card-text"><em>Category: ${data.category_name}</em></p>
                                    <button class="btn btn-warning editMenuItem" data-id="${data.id}">Edit</button>
                                    <button class="btn btn-danger deleteMenuItem" data-id="${data.id}">Delete</button>
                                </div>
                            </div>
                        </div>`;
                    
                    // Append the new item to the menu items container
                    $('.row').append(newItem);
                    $('#addMenuForm')[0].reset(); // Reset the form
                } else {
                    alert(data.message); // Show error message
                }
            },
            error: function() {
                alert('Error adding menu item.');
            }
        });
    });

    // Load edit menu form
    $(document).on('click', '.editMenuItem', function() {
        var itemId = $(this).data('id');
        $.get('processes/edit_menu_item_form.php', { id: itemId }, function(data) {
            $('#editMenuContainer').html(data);
        });
    });

    // Handle save edited menu item
    $(document).on('submit', '#editMenuForm', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: 'processes/edit_menu_item.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    // Update the specific card with new data
                    var updatedCard = `
                        <div class="col-md-4">
                            <div class="card">
                                <img src="${data.image}" alt="Menu Item Image" class="card-img-top" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">${data.name}</h5>
                                    <p class="card-text">${data.description}</p>
                                    <p class="card-text"><strong>Price: $${data.price}</strong></p>
                                    <p class="card-text"><em>Category: ${data.category_name}</em></p>
                                    <button class="btn btn-warning editMenuItem" data-id="${data.id}">Edit</button>
                                    <button class="btn btn-danger deleteMenuItem" data-id="${data.id}">Delete</button>
                                </div>
                            </div>
                        </div>`;
                    
                    // Replace the old card with the updated one
                    $('.editMenuItem[data-id="' + data.id + '"]').closest('.col-md-4').replaceWith(updatedCard);
                    $('#editMenuContainer').empty(); // Clear the edit form
                } else {
                    alert(data.message); // Show error message
                }
            },
            error: function() {
                alert('Error updating menu item.');
            }
        });
    });

    // Delete menu item
    $(document).on('click', '.deleteMenuItem', function() {
        if (confirm('Are you sure you want to delete this item?')) {
            var itemId = $(this).data('id');
            $.post('processes/delete_menu_item.php', { id: itemId }, function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    // Remove the card from the UI
                    $('.deleteMenuItem[data-id="' + itemId + '"]').closest('.col-md-4').remove();
                } else {
                    alert(data.message); // Show error message
                }
            });
        }
    });
</script>


</body>
</html>
