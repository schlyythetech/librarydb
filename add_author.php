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

<!-- add_author.php -->
<?php
include 'database.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $middleName = $_POST['middleName'];
    $stmt = $conn->prepare("INSERT INTO Authors (FirstName, LastName, MiddleName) VALUES (?, ?, ?)");
    $stmt->execute([$firstName, $lastName, $middleName]);
    header("Location: manage_authors.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Author</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Add Author</h2>
        <form method="post">
            <input type="text" name="firstName" class="form-control" placeholder="First Name" required>
            <input type="text" name="lastName" class="form-control mt-2" placeholder="Last Name" required>
            <input type="text" name="middleName" class="form-control mt-2" placeholder="Middle Name">
            <button type="submit" class="btn btn-success mt-2">Add Author</button>
        </form>
    </div>
</body>
</html>