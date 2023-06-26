<?php
require_once 'server.php';

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the logs from the database for the logged-in user
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM logs WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = $conn->query($query);

// Check if there are any logs
if ($result && $result->num_rows > 0) {
    // Fetch the logs into an array
    $logs = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $logs = [];
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Logs | Contact Management System</title>
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

    .container {
        width: 80%;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    }

    h3 {
        margin-top: 0;
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
    <div class="container">
        <h3>Logs</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Action</th>
                <th>User ID</th>
                <th>Date/Time</th>
            </tr>
            <?php
            // Output the logs
            foreach ($logs as $log) {
                echo "<tr>";
                echo "<td>{$log['id']}</td>";
                echo "<td>{$log['action']}</td>";
                echo "<td>{$log['user_id']}</td>";
                echo "<td>{$log['created_at']}</td>";
                echo "</tr>";
            }

            if (empty($logs)) {
                echo "<tr><td colspan='4'>No logs found.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>

</html>