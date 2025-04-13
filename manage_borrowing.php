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
    die("Connection failed: " . $e->getMessage());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $memberID = $_POST['member_id'];
        $bookID = $_POST['book_id'];
        $borrowDate = $_POST['borrow_date'];
        
        $stmt = $conn->prepare("INSERT INTO BorrowingRecords (MemberID, BookID, BorrowDate) VALUES (?, ?, ?)");
        $stmt->execute([$memberID, $bookID, $borrowDate]);
    }
    if (isset($_POST['delete'])) {
        $recordID = $_POST['record_id'];
        $stmt = $conn->prepare("DELETE FROM BorrowingRecords WHERE RecordID = ?");
        $stmt->execute([$recordID]);
    }
}

$borrowingRecords = $conn->query("SELECT * FROM BorrowingRecords")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Borrowing Records</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* Library-Themed Background */
        body {
            background-color: #2c3e50; /* Dark Greenish-Blue */
            color: #ecf0f1; /* Light Gray Text */
        }

        .container {
            background-color: #34495e; /* Darker Grayish-Blue */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
        }

        h2, h3 {
            color: #f1c40f; /* Gold Title for Classic Library Look */
            text-align: center;
        }

        .table {
            background-color: #2c3e50; /* Dark Table for Readability */
            color: #ffffff;
        }

        .table thead {
            background-color: #16a085; /* Deep Green Header */
            color: #ffffff;
        }

        .card {
            background-color: #1a252f; /* Darker Library Theme */
            color: #ffffff;
            border: 1px solid #16a085;
        }

        .btn-custom {
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            transform: scale(1.05);
        }

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

        .btn-primary { background-color: #3498db; border: none; }
        .btn-success { background-color: #2ecc71; border: none; }
        .btn-danger { background-color: #e74c3c; border: none; }
        .btn-warning { background-color: #f39c12; border: none; color: #fff; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>📖 Manage Borrowing Records</h2>
        
        <div class="card mt-4 p-3">
            <h5 class="text-center">➕ Add Borrowing Record</h5>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">👤 Member ID</label>
                    <input type="number" name="member_id" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">📚 Book ID</label>
                    <input type="number" name="book_id" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">📅 Borrow Date</label>
                    <input type="date" name="borrow_date" class="form-control" required>
                </div>
                <button type="submit" name="add" class="btn btn-success w-100 btn-custom">Add Borrowing Record</button>
            </form>
        </div>

        <h3 class="mt-5">📜 Existing Borrowing Records</h3>
        <table class="table table-hover table-bordered mt-3">
            <thead class="table-dark">
                <tr>
                    <th>🔢 Record ID</th>
                    <th>👤 Member ID</th>
                    <th>📚 Book ID</th>
                    <th>📅 Borrow Date</th>
                    <th>📅 Return Date</th>
                    <th>⚙️ Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($borrowingRecords as $record) { ?>
                    <tr>
                        <td><?php echo $record['RecordID']; ?></td>
                        <td><?php echo $record['MemberID']; ?></td>
                        <td><?php echo $record['BookID']; ?></td>
                        <td><?php echo $record['BorrowDate']; ?></td>
                        <td><?php echo $record['ReturnDate'] ?? 'Not Returned'; ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="record_id" value="<?php echo $record['RecordID']; ?>">
                                <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <a href="index.php" class="btn btn-secondary btn-custom">Back to Dashboard</a>
    </div>
</body>
</html>
