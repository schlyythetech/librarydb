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

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $email = $_POST['email'];
        $stmt = $conn->prepare("INSERT INTO Members (FirstName, LastName, Email) VALUES (?, ?, ?)");
        $stmt->execute([$firstName, $lastName, $email]);
    }
    if (isset($_POST['delete'])) {
        $memberID = $_POST['member_id'];
        $stmt = $conn->prepare("DELETE FROM Members WHERE MemberID = ?");
        $stmt->execute([$memberID]);
    }
}
$members = $conn->query("SELECT * FROM Members")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Members</title>
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
        <div class="d-flex justify-content-between align-items-center">
            <h2>👥 Manage Members</h2>
            <a href="index.php" class="btn btn-secondary btn-custom">Back to Dashboard</a>
        </div>

        <!-- Add Member Form -->
        <div class="card mt-4 p-3">
            <h5 class="text-center">➕ Add a New Member</h5>
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">👤 First Name</label>
                        <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">👤 Last Name</label>
                        <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">📧 Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" name="add" class="btn btn-success w-100 btn-custom">Add Member</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Members Table -->
        <h3 class="mt-5">📜 Members List</h3>
        <table class="table table-hover table-bordered mt-3">
            <thead class="table-dark">
                <tr>
                    <th>🔢 Member ID</th>
                    <th>👤 First Name</th>
                    <th>👤 Last Name</th>
                    <th>📧 Email</th>
                    <th>⚙️ Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $member) { ?>
                    <tr>
                        <td><?php echo $member['MemberID']; ?></td>
                        <td><?php echo $member['FirstName']; ?></td>
                        <td><?php echo $member['LastName']; ?></td>
                        <td><?php echo $member['Email']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="member_id" value="<?php echo $member['MemberID']; ?>">
                                <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            <a href="edit_member.php?id=<?php echo $member['MemberID']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
