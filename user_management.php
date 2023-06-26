<?php
require_once 'server.php';

// Check if the user is not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle add user form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    // Retrieve form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Insert the new user into the database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    $conn->query($query);
}

// Handle delete user action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['user_id'])) {
    // Get the user ID from the URL parameter
    $user_id = $_GET['user_id'];

    // Delete the user from the database
    $query = "DELETE FROM users WHERE id = '$user_id'";
    $conn->query($query);
}

// Handle edit user form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user'])) {
    // Retrieve form data
    $user_id = $_POST['user_id'];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Update the user in the database
    $query = "UPDATE users SET username = '$username', email = '$email', password = '$password' WHERE id = '$user_id'";
    $conn->query($query);
}

$query = "SELECT * FROM users";
$result = $conn->query($query);
$users = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>User Management | Contact Management System</title>
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

    h2,
    h3 {
        margin: 20px 0;
    }

    form {
        margin-bottom: 20px;
    }

    label {
        display: inline-block;
        width: 100px;
        margin-right: 10px;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 200px;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
    }

    .edit {
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        text-decoration: none;
    }

    .edit:hover {
        background-color: #45a049;
    }

    .delete {
        background-color: red;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        text-decoration: none;
    }

    .delete:hover {
        background-color: #AA4A44;
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
    <h2>User Management</h2>
    <h3>Add User</h3>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br>

        <input type="submit" name="add_user" value="Add User">
    </form>

    <h3>Users</h3>
    <table>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $user) { ?>
            <tr>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td>
                    <a href="edit_user.php?user_id=<?php echo $user['id']; ?>" class="edit">Edit</a>
                    <a href="?action=delete&user_id=<?php echo $user['id']; ?>" class="delete">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>