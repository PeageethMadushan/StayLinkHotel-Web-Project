<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include 'config.php';

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$sql = "SELECT r.id, r.user_id, r.rating, r.comment, r.created_at FROM reviews r";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Failed to prepare statement: " . $conn->error);
}

$stmt->execute();
$result = $stmt->get_result();
$reviews = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reviews - The Staylink Hotel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            padding: 20px;
        }
        .container {
            margin-top: 30px;
            padding: 20px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }
        h2 {
            color: #007AFF;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            background-color: #ffffff;
            border-radius: 15px; /* Rounded corners */
            margin-top: 20px;
            overflow: hidden; /* Prevent overflow from rounded corners */
        }
        th, td {
            padding: 15px;
            text-align: center;
            transition: background-color 0.3s ease;
        }
        th {
            background-color: #4a4e69;
            color: #fff;
        }
        td:hover {
            background-color: #d9d9d9; /* Unique hover effect */
        }
        .btn-danger {
            background-color: #FF3B30;
            border: none;
            transition: background-color 0.3s, transform 0.2s;
            border-radius: 50px;
            padding: 5px 10px;
        }
        .btn-danger:hover {
            background-color: #FF453A;
            transform: scale(1.05);
        }
        .alert {
            display: none;
            margin-top: 20px;
        }
        @media (max-width: 768px) {
            h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Reviews</h2>
    <div class="alert alert-success" id="successAlert"></div>
    <div class="alert alert-danger" id="errorAlert"></div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Comment</th>
                <th>Rating</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reviews as $review) : ?>
                <tr id="review-row-<?= $review['id'] ?>">
                    <td><?= $review['id'] ?></td>
                    <td><?= $review['user_id'] ?></td>
                    <td><?= htmlspecialchars($review['comment']) ?></td>
                    <td><?= $review['rating'] ?></td>
                    <td><?= $review['created_at'] ?></td>
                    <td>
                        <button class="btn btn-danger btn-sm delete-review-btn" data-review-id="<?= $review['id'] ?>">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
$(document).ready(function() {
    $('.delete-review-btn').click(function() {
        const reviewId = $(this).data('review-id');
        const confirmed = confirm('Are you sure you want to delete this review?');

        if (confirmed) {
            $.ajax({
                url: 'processes/delete_review.php',
                type: 'POST',
                data: { review_id: reviewId },
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
                        if (data.success) {
                            $('#successAlert').text('Review deleted successfully.').fadeIn().delay(3000).fadeOut();
                            $('#review-row-' + reviewId).fadeOut(500, function() {
                                $(this).remove();
                            });
                        } else {
                            $('#errorAlert').text('Error deleting review: ' + data.message).fadeIn().delay(3000).fadeOut();
                        }
                    } catch (e) {
                        $('#errorAlert').text('Unexpected response from server.').fadeIn().delay(3000).fadeOut();
                    }
                },
                error: function() {
                    $('#errorAlert').text('Error occurred while deleting the review.').fadeIn().delay(3000).fadeOut();
                }
            });
        }
    });
});
</script>

</body>
</html>
