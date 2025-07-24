<!-- order_management.php -->
<?php
include 'config.php';
$status_options = ['Pending', 'Processing', 'Completed', 'Cancelled'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #1c1c1e; /* Dark background */
            font-family: 'San Francisco', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #fff; /* White font */
        }
        .container {
            background-color: #2c2c2e; /* Dark container background */
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
            margin-top: 50px;
        }
        h1 {
            font-weight: 600;
            color: #ffffff;
            text-align: center;
            margin-bottom: 40px;
        }
        .table-container {
            background-color: #333333; /* Dark table background */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.6);
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            color: #fff;
        }
        thead {
            background-color: #444444; /* Dark header */
        }
        thead th {
            color: #ffffff; /* White text */
            padding: 12px;
        }
        tbody tr {
            transition: background 0.2s ease;
        }
        tbody td {
            padding: 12px;
        }
        .filter-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        #statusFilter {
            width: 200px;
            background-color: #444444;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 8px;
        }
        #statusFilter:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
        }
        .statusSelect {
            background-color: #444444;
            color: #fff;
            border: none;
            padding: 8px;
            border-radius: 6px;
        }
        .statusSelect:hover {
            background-color: #555555;
        }
        .statusSelect:focus {
            outline: none;
            box-shadow: 0 0 8px rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Order Management</h1>
        
        <div class="filter-container">
            <select id="statusFilter" class="form-control">
                <option value="">All Orders</option>
                <?php foreach ($status_options as $status): ?>
                    <option value="<?php echo htmlspecialchars($status); ?>">
                        <?php echo htmlspecialchars($status); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User ID</th>
                        <th>User Name</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody id="orderTableBody">
                    <!-- Order rows will be populated here via AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function loadOrders(status) {
                $.ajax({
                    url: 'processes/fetch_orders.php',
                    type: 'GET',
                    data: { status: status },
                    success: function(data) {
                        $('#orderTableBody').html(data);
                    },
                    error: function() {
                        alert('Error loading orders.');
                    }
                });
            }

            loadOrders('');

            $('#statusFilter').change(function() {
                var status = $(this).val();
                loadOrders(status);
            });

            $('#orderTableBody').on('change', '.statusSelect', function() {
                var orderId = $(this).data('id');
                var status = $(this).val();

                $.ajax({
                    type: 'POST',
                    url: 'processes/update_order_status.php',
                    data: { order_id: orderId, status: status },
                    success: function(response) {
                        if (response.includes("successfully")) {
                            alert('Order status updated.');
                            loadOrders($('#statusFilter').val());
                        } else {
                            alert('Error updating status.');
                        }
                    },
                    error: function() {
                        alert('Error updating status.');
                    }
                });
            });
        });
    </script>
</body>
</html>
