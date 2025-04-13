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
?>

<!-- index.php - Main Page -->
<?php
include 'database.php';
$books = $conn->query("SELECT Books.BookID, Books.Title, Books.ISBN, Books.PublishedYear, Authors.FirstName, Authors.LastName FROM Books INNER JOIN Authors ON Books.AuthorID = Authors.AuthorID")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* Library-Themed Background */
        body {
            background-color: #2c3e50; /* Dark Greenish Blue */
            color: #ecf0f1; /* Light Grey Text */
        }
        
        .card {
            background-color: #34495e; /* Darker Shade for Contrast */
            color: #ffffff;
        }
        
        .card-header {
            background-color: #1abc9c; /* Turquoise Green */
            color: #ffffff;
        }

        .table thead {
            background-color: #16a085; /* Deep Green */
            color: #ffffff;
        }

        .table tbody tr {
            background-color: #2c3e50; /* Darker background for better contrast */
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

        .dashboard-title {
            font-family: 'Georgia', serif;
            font-weight: bold;
            color: #f1c40f; /* Gold - Symbolizing Knowledge */
        }

        /* Button Styling */
        .btn-primary { background-color: #3498db; border: none; }
        .btn-success { background-color: #2ecc71; border: none; }
        .btn-warning { background-color: #f39c12; border: none; }
        .btn-danger { background-color: #e74c3c; border: none; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center dashboard-title mb-4">📚 Library System Dashboard</h1>
        
        <!-- Card Section -->
        <div class="card shadow-lg">
            <div class="card-header text-center">
                <h4>📖 Books and Authors</h4>
            </div>
            <div class="card-body">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>📌 Book ID</th>
                            <th>📚 Title</th>
                            <th>👤 Author</th>
                            <th>🔖 ISBN</th>
                            <th>📅 Published Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $book) { ?>
                            <tr>
                                <td><?= $book['BookID'] ?></td>
                                <td><?= $book['Title'] ?></td>
                                <td><?= $book['FirstName'] . ' ' . $book['LastName'] ?></td>
                                <td><?= $book['ISBN'] ?></td>
                                <td><?= $book['PublishedYear'] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="btn-group d-flex justify-content-center mt-4" role="group">
            <a href="manage_authors.php" class="btn btn-primary btn-custom">Manage Authors</a>
            <a href="manage_books.php" class="btn btn-success btn-custom">Manage Books</a>
            <a href="manage_members.php" class="btn btn-warning btn-custom">Manage Members</a>
            <a href="manage_borrowing.php" class="btn btn-danger btn-custom">Manage Borrowing Records</a>
        </div>
    </div>
</body>
</html>
