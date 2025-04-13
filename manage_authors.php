<?php
// database.php - Database connection
$host = 'localhost';
$dbname = 'librarydb';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $middleName = $_POST['middle_name'];
        
        $stmt = $conn->prepare("INSERT INTO Authors (FirstName, LastName, MiddleName) VALUES (?, ?, ?)");
        $stmt->execute([$firstName, $lastName, $middleName]);
    }
    
    if (isset($_POST['edit'])) {
        $authorID = $_POST['author_id'];
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $middleName = $_POST['middle_name'];
        
        $stmt = $conn->prepare("UPDATE Authors SET FirstName = ?, LastName = ?, MiddleName = ? WHERE AuthorID = ?");
        $stmt->execute([$firstName, $lastName, $middleName, $authorID]);
    }
    
    if (isset($_POST['delete'])) {
        $authorID = $_POST['author_id'];
        $stmt = $conn->prepare("DELETE FROM Authors WHERE AuthorID = ?");
        $stmt->execute([$authorID]);
    }
}

$authors = $conn->query("SELECT * FROM authors")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Authors</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* Library-Themed Background */
        body {
            background-color: #2c3e50; /* Dark Greenish-Blue */
            color: #ecf0f1; /* Light Grey Text */
        }

        .container {
            background-color: #34495e; /* Darker Card */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
        }

        h2, h3 {
            color: #f1c40f; /* Gold - Represents Knowledge */
            text-align: center;
        }

        .table {
            background-color: #2c3e50; /* Darker for Readability */
            color: #ffffff;
        }

        .table thead {
            background-color: #16a085; /* Deep Green */
            color: #ffffff;
        }

        .btn-custom {
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            transform: scale(1.05);
        }

        .btn-group a {
            margin-right: 10px;
        }

        .btn-primary { background-color: #3498db; border: none; }
        .btn-success { background-color: #2ecc71; border: none; }
        .btn-warning { background-color: #f39c12; border: none; }
        .btn-danger { background-color: #e74c3c; border: none; }

        .form-control {
            background-color: #2c3e50;
            color: #ffffff;
            border: 1px solid #16a085;
        }

        .form-control:focus {
            background-color: #2c3e50;
            color: #ffffff;
            border-color: #f1c40f;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>📖 Manage Authors</h2>

        <form method="POST" class="mt-3">
            <input type="hidden" name="author_id" id="author_id">
            <div class="mb-3">
                <label class="form-label">First Name</label>
                <input type="text" name="first_name" id="first_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Middle Name</label>
                <input type="text" name="middle_name" id="middle_name" class="form-control">
            </div>
            <button type="submit" name="add" class="btn btn-success btn-custom">Add Author</button>
            <button type="submit" name="edit" class="btn btn-warning btn-custom">Update Author</button>
        </form>

        <h3 class="mt-5">Existing Authors</h3>
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>📌 Author ID</th>
                    <th>👤 Name</th>
                    <th>⚙️ Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($authors as $author) { ?>
                    <tr>
                        <td><?php echo $author['AuthorID']; ?></td>
                        <td><?php echo $author['FirstName'] . ' ' . $author['MiddleName'] . ' ' . $author['LastName']; ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="editAuthor(<?php echo $author['AuthorID']; ?>, '<?php echo $author['FirstName']; ?>', '<?php echo $author['LastName']; ?>', '<?php echo $author['MiddleName']; ?>')">Edit</button>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="author_id" value="<?php echo $author['AuthorID']; ?>">
                                <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-secondary btn-custom">Back to Dashboard</a>
    </div>

    <script>
        function editAuthor(id, firstName, lastName, middleName) {
            document.getElementById('author_id').value = id;
            document.getElementById('first_name').value = firstName;
            document.getElementById('last_name').value = lastName;
            document.getElementById('middle_name').value = middleName;
        }
    </script>
</body>
</html>
