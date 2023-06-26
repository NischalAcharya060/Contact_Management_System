<?php
require_once 'server.php';

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Function to fetch the list of contacts for the logged-in user
function getContacts($conn, $user_id)
{
    $query = "SELECT id, name, email, phone, address, document_type, document AS document_name FROM contacts WHERE user_id = '$user_id'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    return [];
}


// Fetch the list of contacts for the logged-in user
$contacts = getContacts($conn, $_SESSION['user_id']);

// Handle form submission for adding/editing/deleting contacts
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_contact'])) {
        // Handle adding a new contact

        // Retrieve form data
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $documentType = $_POST['document_type'];

        // Upload the document file
        $document = $_FILES['document']['name'];
        $documentTmpName = $_FILES['document']['tmp_name'];
        $documentError = $_FILES['document']['error'];

        if ($documentError === 0) {
            $documentDestination = 'documents/' . $document;
            move_uploaded_file($documentTmpName, $documentDestination);
        } else {
        }

        // Insert the contact into the database
        $insertQuery = "INSERT INTO contacts (user_id, name, email, phone, address, document, document_type) VALUES ('{$_SESSION['user_id']}', '$name', '$email', '$phone', '$address', '$document', '$documentType')";
        $conn->query($insertQuery);

        // Insert a log entry for the action
        $logAction = "Added a contact";
        $logUserId = $_SESSION['user_id'];
        $logQuery = "INSERT INTO logs (action, user_id) VALUES ('$logAction', '$logUserId')";
        $conn->query($logQuery);

        // Redirect to the contact page to prevent form resubmission
        header("Location: contact.php");
        exit();
    } elseif (isset($_POST['edit_contact'])) {
        // Handle editing an existing contact

        // Retrieve form data
        $contactId = $_POST['contact_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        // Update the contact in the database
        $updateQuery = "UPDATE contacts SET name = '$name', email = '$email', phone = '$phone', address = '$address' WHERE id = '$contactId' AND user_id = '{$_SESSION['user_id']}'";
        $conn->query($updateQuery);

        // Insert a log entry for the action
        $logAction = "Edited a contact";
        $logUserId = $_SESSION['user_id'];
        $logQuery = "INSERT INTO logs (action, user_id) VALUES ('$logAction', '$logUserId')";
        $conn->query($logQuery);

        // Redirect to the contact page to prevent form resubmission
        header("Location: contact.php");
        exit();
    } elseif (isset($_POST['delete_contact'])) {
        // Handle deleting an existing contact
        $contactId = $_POST['contact_id'];

        // Delete the contact from the database
        $deleteQuery = "DELETE FROM contacts WHERE id = '$contactId' AND user_id = '{$_SESSION['user_id']}'";
        $conn->query($deleteQuery);

        // Insert a log entry for the action
        $logAction = "Deleted a contact";
        $logUserId = $_SESSION['user_id'];
        $logQuery = "INSERT INTO logs (action, user_id) VALUES ('$logAction', '$logUserId')";
        $conn->query($logQuery);

        // Redirect to the contact page to prevent form resubmission
        header("Location: contact.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Contact | Contact Management System</title>
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

    .container {
        margin: 20px;
    }

    h3 {
        color: #333;
        margin-bottom: 10px;
        text-align: center;
    }

    form {
        margin-bottom: 20px;
        text-align: center;
    }

    label {
        display: inline-block;
        margin-bottom: 5px;
        color: #555;
        text-align: right;
        width: 150px;
    }

    input[type="text"],
    input[type="email"],
    input[type="file"],
    select {
        width: 300px;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 3px;
        margin-bottom: 10px;
        font-size: 14px;
        display: inline-block;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    a {
        text-decoration: none;
    }

    .delete-form {
        display: inline-block;
    }

    .btn,
    .edit {
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    .btn:hover,
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
    }

    .delete:hover {
        background-color: #AA4A44;
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
        <h3>Add/Edit Contacts</h3>
        <?php
        // Check if a contact ID is provided for editing
        if (isset($_GET['contact_id'])) {
            $contactId = $_GET['contact_id'];

            // Retrieve the contact details from the database
            $query = "SELECT * FROM contacts WHERE id = '$contactId' AND user_id = '{$_SESSION['user_id']}'";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                $contact = $result->fetch_assoc();
        ?>
                <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" value="<?php echo $contact['name']; ?>" required><br>

                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" value="<?php echo $contact['email']; ?>" required><br>

                    <label for="phone">Phone:</label>
                    <input type="text" name="phone" id="phone" value="<?php echo $contact['phone']; ?>" required><br>

                    <label for="address">Address:</label>
                    <input type="text" name="address" id="address" value="<?php echo $contact['address']; ?>" required><br>

                    <input type="hidden" name="contact_id" value="<?php echo $contact['id']; ?>">
                    <input type="submit" name="edit_contact" value="Update Contact">
                </form>
            <?php
            } else {
                echo "Contact not found.";
            }
        } else {
            ?>
            <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" enctype="multipart/form-data">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required><br>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required><br>

                <label for="phone">Phone:</label>
                <input type="text" name="phone" id="phone" required><br>

                <label for="address">Address:</label>
                <input type="text" name="address" id="address" required><br>

                <label for="document_type">Document Type:</label>
                <select name="document_type" id="document_type" required>
                    <option value="Passport">Passport</option>
                    <option value="ID Card">ID Card</option>
                    <option value="Driving License">Driving License</option>
                </select><br>

                <label for="document">Document:</label>
                <input type="file" name="document" id="document" accept=".jpeg, .jpg, .doc, .docx" required><br>


                <input class="btn" type="submit" name="add_contact" value="Add Contact">
            </form>
        <?php
        }
        ?>
        <br>
        <h3>Contact List</h3>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Document Type</th>
                <th>Document Name</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($contacts as $contact) { ?>
                <tr>
                    <td><?php echo $contact['name']; ?></td>
                    <td><?php echo $contact['email']; ?></td>
                    <td><?php echo $contact['phone']; ?></td>
                    <td><?php echo $contact['address']; ?></td>
                    <td><?php echo $contact['document_type']; ?></td>
                    <td><?php echo $contact['document_name']; ?></td>
                    <td>
                        <a href="contact.php?contact_id=<?php echo $contact['id']; ?>" class="edit">Edit</a>
                        <form method="post" style="display: inline-block;">
                            <input type="hidden" name="contact_id" value="<?php echo $contact['id']; ?>">
                            <input type="submit" class="delete" name="delete_contact" value="Delete" onclick="return confirm('Are you sure you want to delete this contact?')">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>

    </div>
</body>

</html>