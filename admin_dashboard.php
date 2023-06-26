<?php
require_once 'server.php';

// Check if the admin is not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Get admin information
$admin_id = $_SESSION['admin_id'];

// Retrieve admin data from the database
$query = "SELECT * FROM admins WHERE admin_id = '$admin_id'";
$result = $conn->query($query);
if ($result && $result->num_rows > 0) {
    $admin = $result->fetch_assoc();
} else {
    // Redirect if admin data not found
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $newUsername = $_POST["new_username"];
    $newEmail = $_POST["new_email"];

    // Check if username is updated
    if ($newUsername != $admin['admin_name']) {
        // Insert a log entry for username update
        $logMessage = "Username updated from '{$admin['admin_name']}' to '$newUsername'";
        $escapedLogMessage = $conn->real_escape_string($logMessage);

        $insertLogQuery = "INSERT INTO logs (admin_id, action) VALUES ('$admin_id', '$escapedLogMessage')";
        $conn->query($insertLogQuery);

        // Update username
        $updateUsernameQuery = "UPDATE admins SET admin_name = '$newUsername' WHERE id = '$admin_id'";
        $conn->query($updateUsernameQuery);
    }

    // Check if email is updated
    if ($newEmail != $admin['email']) {
        // Update email
        $updateEmailQuery = "UPDATE admins SET email = '$newEmail' WHERE id = '$admin_id'";
        $conn->query($updateEmailQuery);
    }

    $_SESSION['profile_updated'] = true;
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard | Contact Management System</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    nav {
        background-color: #333;
        padding: 10px;
    }

    .navigation-bar ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }

    .navigation-bar li {
        display: inline-block;
        margin-right: 10px;
    }

    .navigation-bar a {
        color: white;
        text-decoration: none;
        padding: 8px;
    }

    .admin-information {
        margin: 20px;
    }

    .admin-information h2 {
        color: #333;
        margin-bottom: 10px;
    }

    .admin-information p {
        margin: 5px 0;
    }

    form {
        margin-top: 20px;
        text-align: left;
    }

    label {
        display: block;
        margin-bottom: 5px;
        color: #555;
        text-align: left;
    }

    input[type="text"],
    input[type="email"] {
        width: 300px;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 3px;
        margin-bottom: 10px;
        font-size: 14px;
    }

    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 8px 12px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 14px;
        text-transform: uppercase;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }
</style>

<body>
    <nav>
        <div class="navigation-bar">
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="user_management.php">User Management</a></li>
                <li><a href="admin_logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="admin-information">
        <h2>Admin Information</h2>
        <p>Admin ID: <?php echo $admin['admin_id']; ?></p>
        <p>Email: <?php echo $admin['email']; ?></p>
    </div>

    <!-- Update Form -->
    <div>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <label for="new_email">Update Email:</label>
            <input type="email" name="new_email" id="new_email" value="<?php echo $admin['email']; ?>" required><br>

            <input type="submit" value="Update">
        </form>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $newEmail = $_POST["new_email"];

        // Check if email is updated
        if ($newEmail != $admin['email']) {
            // Insert a log entry for email update
            $logMessage = "Email updated from '{$admin['email']}' to '$newEmail'";
            $escapedLogMessage = $conn->real_escape_string($logMessage);

            $insertLogQuery = "INSERT INTO logs (admin_id, action) VALUES ('$admin_id', '$escapedLogMessage')";
            $conn->query($insertLogQuery);

            // Update email
            $updateEmailQuery = "UPDATE admins SET email = '$newEmail' WHERE admin_id = '$admin_id'";
            $conn->query($updateEmailQuery);
        }

        // Redirect to dashboard
        header("Location: admin_dashboard.php");
        exit();
    }
    ?>

    <!-- Display success message if email updated -->
    <?php if (isset($_SESSION['email_updated'])) { ?>
        <script>
            // Display success message using SweetAlert
            Swal.fire({
                title: 'Success',
                text: 'Email updated successfully!',
                icon: 'success',
                timer: 3000
            });
        </script>
    <?php
        // Remove the success message flag from the session
        unset($_SESSION['email_updated']);
    }
    ?>
</body>

</html>