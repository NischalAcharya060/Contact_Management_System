<?php
require_once 'server.php';

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user information
$user_id = $_SESSION['user_id'];

// Retrieve user data from the database
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = $conn->query($query);
if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // Redirect if user data not found
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $newUsername = $_POST["new_username"];
    $newEmail = $_POST["new_email"];

    // Check if username is updated
    if ($newUsername != $user['username']) {
        // Insert a log entry for username update
        $logMessage = "Username updated from '{$user['username']}' to '$newUsername'";
        $escapedLogMessage = $conn->real_escape_string($logMessage);

        $insertLogQuery = "INSERT INTO logs (user_id, action) VALUES ('$user_id', '$escapedLogMessage')";
        $conn->query($insertLogQuery);

        // Update username
        $updateUsernameQuery = "UPDATE users SET username = '$newUsername' WHERE id = '$user_id'";
        $conn->query($updateUsernameQuery);
    }

    // Check if email is updated
    if ($newEmail != $user['email']) {
        // Insert a log entry for email update
        $logMessage = "Email updated from '{$user['email']}' to '$newEmail'";
        $escapedLogMessage = $conn->real_escape_string($logMessage);

        $insertLogQuery = "INSERT INTO logs (user_id, action) VALUES ('$user_id', '$escapedLogMessage')";
        $conn->query($insertLogQuery);

        // Update email
        $updateEmailQuery = "UPDATE users SET email = '$newEmail' WHERE id = '$user_id'";
        $conn->query($updateEmailQuery);
    }

    // Check if a new profile picture is uploaded
    if (!empty($_FILES["profile_picture"]["name"])) {
        $targetDirectory = "uploads/";
        $profilePicture = $_FILES["profile_picture"]["name"];
        $targetFile = $targetDirectory . basename($profilePicture);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if the uploaded file is a valid JPG or JPEG image
        if ($imageFileType == "jpg" || $imageFileType == "jpeg") {
            move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile);

            // Update profile picture
            $updateProfilePictureQuery = "UPDATE users SET profile_picture = '$profilePicture' WHERE id = '$user_id'";
            $conn->query($updateProfilePictureQuery);
        } else {
            $_SESSION['profile_update_error'] = "Invalid file format. Only JPG and JPEG files are allowed.";
            header("Location: dashboard.php");
            exit();
        }
    }

    $_SESSION['profile_updated'] = true;
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard | Contact Management System</title>
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

    .user-information {
        margin: 20px;
    }

    .user-information h2 {
        color: #333;
        margin-bottom: 10px;
    }

    .user-information p {
        margin: 5px 0;
    }

    .user-information img {
        margin-top: 20px;
        border-radius: 5px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
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
    input[type="email"],
    input[type="file"] {
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
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="contact.php">Contacts</a></li>
                <li><a href="logs.php">Logs</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="user-information">
        <h2>User Information</h2>
        <p>Username: <?php echo $user['username']; ?></p>
        <p>Email: <?php echo $user['email']; ?></p>
        <img src="uploads/<?php echo $user['profile_picture']; ?>" alt="Profile Picture" width="200">
    </div>

    <!-- Update Form -->
    <div>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" enctype="multipart/form-data">
            <label for="new_username">Username:</label>
            <input type="text" name="new_username" id="new_username" value="<?php echo $user['username']; ?>" required><br>

            <label for="new_email">Email:</label>
            <input type="email" name="new_email" id="new_email" value="<?php echo $user['email']; ?>" required><br>

            <label for="profile_picture">Profile Picture (JPG/JPEG only):</label>
            <input type="file" name="profile_picture" id="profile_picture"><br>

            <input type="submit" value="Update">
        </form>
    </div>

    <!-- Display success message if profile updated -->
    <?php if (isset($_SESSION['profile_updated'])) { ?>
        <script>
            // Display success message using SweetAlert
            Swal.fire({
                title: 'Success',
                text: 'Profile updated successfully!',
                icon: 'success',
                timer: 3000
            });
        </script>
    <?php
        // Remove the success message flag from the session
        unset($_SESSION['profile_updated']);
    }

    // Display error message if profile update encountered an error
    if (isset($_SESSION['profile_update_error'])) {
        echo '<script>
            // Display error message using SweetAlert
            Swal.fire({
                title: "Error",
                text: "' . $_SESSION['profile_update_error'] . '",
                icon: "error",
                timer: 5000
            });
        </script>';
        // Remove the error message from the session
        unset($_SESSION['profile_update_error']);
    }
    ?>
</body>

</html>