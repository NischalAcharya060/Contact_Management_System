<?php
require_once 'server.php';

// Check if the admin is already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

// Check if the admin login form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['admin_login'])) {
    // Retrieve form data
    $adminEmail = $_POST["admin_email"];
    $adminPassword = $_POST["admin_password"];

    // Check admin credentials against the database
    $query = "SELECT * FROM admins WHERE email = '$adminEmail' AND password = '$adminPassword'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        // Admin credentials are valid
        $admin = $result->fetch_assoc();
        $_SESSION['admin_id'] = $admin['admin_id'];
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $adminError = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Login | Contact Management System</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .login {
        width: 400px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f5f5f5;
    }

    h2 {
        text-align: center;
        color: #333;
    }

    form {
        margin-top: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        color: #555;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 3px;
        margin-bottom: 10px;
    }

    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        width: 100%;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    p.error {
        color: red;
        margin-top: 10px;
        text-align: center;
    }

    p.success {
        color: green;
        margin-top: 10px;
        text-align: center;
    }
</style>

<body>
    <div class="login">
    <h2>Admin Login</h2>
    <?php if (isset($adminError)) { ?>
        <p><?php echo $adminError; ?></p>
    <?php } ?>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="admin_email">Email:</label>
        <input type="email" name="admin_email" id="admin_email" required><br>

        <label for="admin_password">Password:</label>
        <input type="password" name="admin_password" id="admin_password" required><br>

        <input type="submit" name="admin_login" value="Login">
    </form>
    <p>if you are not admin?<a href="login.php">Click Here</a></p>
    </div>
</body>
</html>
