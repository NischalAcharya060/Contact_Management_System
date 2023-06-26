<?php
require_once 'server.php';

// Check if the user is not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Check if the user ID is provided in the URL parameter
if (!isset($_GET['user_id'])) {
    header("Location: user_management.php");
    exit();
}

// Retrieve the user ID from the URL parameter
$user_id = $_GET['user_id'];

// Retrieve the user information from the database
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = $conn->query($query);

// Check if the user exists
if ($result->num_rows == 0) {
    header("Location: user_management.php");
    exit();
}

// Fetch the user details
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Update the user in the database
    $query = "UPDATE users SET username = '$username', email = '$email', password = '$password' WHERE id = '$user_id'";
    $conn->query($query);

    // Redirect back to user management page
    header("Location: user_management.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit User</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <h2>Edit User</h2>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" value="<?php echo $user['username']; ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo $user['email']; ?>" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" value="<?php echo $user['password']; ?>" required><br>

        <input type="submit" value="Update">
    </form>
</body>

</html>