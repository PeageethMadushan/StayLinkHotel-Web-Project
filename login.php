<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - The Staylink Hotel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background: url('assets/images/bglg.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            color: #e5e5e5;
        }
        .card {
            width: 400px;
            border-radius: 12px;
            background: rgba(30, 30, 30, 0.8);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .card-header {
            background: rgba(255, 111, 97, 0.8);
            color: #fff;
            text-align: center;
            font-size: 28px;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }
        .form-label {
            font-weight: bold;
            color: #e5e5e5;
        }
        .btn-primary {
            background-color: #ff6f61;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #ff3f3f;
        }
        #loginError {
            color: #ff6f61;
            text-align: center;
            display: none;
        }
        @media (max-width: 576px) {
            .card {
                width: 90%;
            }
        }
        /* Add subtle animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .card {
            animation: fadeIn 0.5s ease-in-out;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <i class="fas fa-sign-in-alt"></i> Login
        </div>
        <div class="card-body">
            <form id="loginForm">
                <div id="loginError"></div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required minlength="6">
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
                <p class="text-center mt-3">
                    <a href="register.php" class="link-secondary">Don't have an account? Register</a>
                </p>
            </form>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#loginForm').submit(function(e) {
            e.preventDefault();

            // Basic client-side validation
            var email = $('#email').val().trim();
            var password = $('#password').val().trim();

            if (email === '' || password === '') {
                $('#loginError').text('Please fill in all fields.').show();
                return;
            }

            if (!validateEmail(email)) {
                $('#loginError').text('Please enter a valid email address.').show();
                return;
            }

            if (password.length < 6) {
                $('#loginError').text('Password must be at least 6 characters long.').show();
                return;
            }

            $.ajax({
                url: 'processes/process_login.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    let jsonResponse = JSON.parse(response);
                    if (jsonResponse.status === 'success') {
                        window.location.href = jsonResponse.redirect;
                    } else {
                        $('#loginError').text(jsonResponse.message).show();
                    }
                },
                error: function() {
                    $('#loginError').text('An error occurred while processing your request.').show();
                }
            });
        });

        function validateEmail(email) {
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
    });
    </script>

</body>
</html>
