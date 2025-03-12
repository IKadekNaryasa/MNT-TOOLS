<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            margin: 0;
            height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #e8f4ff;
            overflow: hidden;
        }

        .login-container {
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

        .login-container h2 {
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

        .login-button {
            width: 100%;
            padding: 10px;
            background-color: #728FCE;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .login-button:hover {
            background-color: #003d80;
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
    <div class="login-container">
        <h2>Login</h2>
        <form action="authenticate" method="post">
            <?php csrf_field(); ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Input Username" autofocus value="<?= old('username'); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Input Password" required>
            </div>
            <button type="submit" class="login-button">Login</button>
        </form>
    </div>
</body>

</html>