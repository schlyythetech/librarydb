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
        $title = $_POST['title'];
        $authorName = $_POST['author_name']; // User-provided author name
        $isbn = $_POST['isbn'];
        $publishedYear = $_POST['published_year'];

        // Split author name into first and last name
        $nameParts = explode(' ', $authorName, 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';

        // Check if the author exists
        $stmt = $conn->prepare("SELECT AuthorID FROM Authors WHERE FirstName = ? AND LastName = ?");
        $stmt->execute([$firstName, $lastName]);
        $author = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($author) {
            // If author exists, get their ID
            $authorID = $author['AuthorID'];
        } else {
            // If author doesn't exist, add them to the Authors table
            $stmt = $conn->prepare("INSERT INTO Authors (FirstName, LastName) VALUES (?, ?)");
            $stmt->execute([$firstName, $lastName]);
            $authorID = $conn->lastInsertId();
        }

        // Insert the book
        $stmt = $conn->prepare("INSERT INTO Books (Title, AuthorID, ISBN, PublishedYear) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $authorID, $isbn, $publishedYear]);
    }

    if (isset($_POST['delete'])) {
        $bookID = $_POST['book_id'];
        $stmt = $conn->prepare("DELETE FROM Books WHERE BookID = ?");
        $stmt->execute([$bookID]);
    }
}

// Fetch books with their authors
$books = $conn->query("SELECT Books.BookID, Books.Title, Books.ISBN, Books.PublishedYear, Authors.FirstName, Authors.LastName FROM Books JOIN Authors ON Books.AuthorID = Authors.AuthorID")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* Library-Themed Background */
        body {
            background-color: #2c3e50; /* Dark Library Green */
            color: #ecf0f1; /* Light Gray Text */
        }

        .container {
            background-color: #34495e; /* Darker Grayish-Blue */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
        }

        h2, h3 {
            color: #f1c40f; /* Golden Title for Classic Look */
            text-align: center;
        }

        .card {
            background-color: #1a252f; /* Darker Library Theme */
            color: #ffffff;
            border: 1px solid #16a085; /* Deep Green Border */
        }

        .table {
            background-color: #2c3e50; /* Darker Table for Readability */
            color: #ffffff;
        }

        .table thead {
            background-color: #16a085; /* Green Headers */
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
        <h2>📚 Manage Books</h2>
        <a href="index.php" class="btn btn-secondary btn-custom mb-3">Back to Dashboard</a>
        
        <!-- Add Book Form -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">➕ Add a New Book</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">📖 Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Book Title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">👤 Author</label>
                        <input type="text" name="author_name" class="form-control" placeholder="Author Name (e.g., John Doe)" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">📕 ISBN</label>
                        <input type="text" name="isbn" class="form-control" placeholder="ISBN" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">📆 Published Year</label>
                        <input type="number" name="published_year" class="form-control" placeholder="Published Year" required>
                    </div>
                    <button type="submit" name="add" class="btn btn-success btn-custom">Add Book</button>
                </form>
            </div>
        </div>

        <!-- Books Table -->
        <h3>📚 Books List</h3>
        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>🔢 ID</th>
                    <th>📖 Title</th>
                    <th>👤 Author</th>
                    <th>📕 ISBN</th>
                    <th>📆 Published Year</th>
                    <th>⚙️ Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book) { ?>
                    <tr>
                        <td><?php echo $book['BookID']; ?></td>
                        <td><?php echo $book['Title']; ?></td>
                        <td><?php echo $book['FirstName'] . ' ' . $book['LastName']; ?></td>
                        <td><?php echo $book['ISBN']; ?></td>
                        <td><?php echo $book['PublishedYear']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="book_id" value="<?php echo $book['BookID']; ?>">
                                <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
