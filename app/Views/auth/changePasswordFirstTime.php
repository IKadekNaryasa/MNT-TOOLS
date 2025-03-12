<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <style>
        body {
            margin: 0;
            height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #e8f4ff;
            overflow: hidden;
        }

        .password-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            padding: 20px;
            background-color: #4863A0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .password-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #fff;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #f0f8ff;
        }

        .form-group input {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 0 auto;
            display: block;
            border: 1px solid #a6d8ff;
            border-radius: 4px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #60b5ff;
        }

        .password-button {
            width: 100%;
            padding: 10px;
            background-color: #728FCE;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .password-button:hover {
            background-color: #003d80;
        }

        .error-message {
            font-size: 12px;
            color: #ff4c4c;
            margin-top: 5px;
            display: none;
        }

        .success-message {
            font-size: 12px;
            color: #4caf50;
            margin-top: 5px;
            display: none;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div>
        <?php if (session('messages_error')): ?>
            <script>
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "<?= session('messages_error'); ?>",
                });
            </script>
        <?php endif; ?>
        <?php if (session('messages')): ?>
            <script>
                Swal.fire({
                    position: "top-center",
                    icon: "success",
                    title: "Success!",
                    text: "<?= session('messages'); ?>",
                    showConfirmButton: false,
                    timer: 1000
                });
            </script>
        <?php endif; ?>
        <?php if (session('errors')): ?>
            <script>
                var errorList = <?php echo json_encode(session('errors')); ?>;
                var errorMessages = '<ul>';
                for (var key in errorList) {
                    if (errorList.hasOwnProperty(key)) {
                        errorMessages += '<li>' + errorList[key] + '</li>';
                    }
                }
                errorMessages += '</ul>';
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    html: errorMessages,
                });
            </script>
        <?php endif; ?>
    </div>
    <div class="password-container">
        <h2>Change Password</h2>
        <form action="changePasswordFirstTime" method="post">
            <?php csrf_field(); ?>
            <div class="form-group">
                <label for="new-password">New Password</label>
                <input type="password" id="new-password" name="newPassword" placeholder="Enter New Password (min 8 charachter)" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm New Password</label>
                <input type="password" id="confirm-password" name="confirmPassword" placeholder="Confirm New Password" required>
                <div class="error-message" id="error-message">Passwords do not match!</div>
                <div class="success-message" id="success-message">Passwords match!</div>
            </div>
            <button type="submit" class="password-button" id="submit-button" disabled>Change Password</button>
        </form>
    </div>

    <script>
        const newPassword = document.getElementById("new-password");
        const confirmPassword = document.getElementById("confirm-password");
        const errorMessage = document.getElementById("error-message");
        const successMessage = document.getElementById("success-message");
        const submitButton = document.getElementById("submit-button");

        function validatePasswords() {
            if (confirmPassword.value === "") {
                errorMessage.style.display = "none";
                successMessage.style.display = "none";
                submitButton.disabled = true;
            } else if (newPassword.value === confirmPassword.value) {
                errorMessage.style.display = "none";
                successMessage.style.display = "block";
                submitButton.disabled = false;
            } else {
                errorMessage.style.display = "block";
                successMessage.style.display = "none";
                submitButton.disabled = true;
            }
        }

        newPassword.addEventListener("input", validatePasswords);
        confirmPassword.addEventListener("input", validatePasswords);
    </script>
</body>

</html>