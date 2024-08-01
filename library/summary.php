<?php
// Database connection
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "library_connect"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve details from URL parameters
$book_title = urldecode($_GET['book_title']);
$return_date = urldecode($_GET['return_date']);
$return_time = urldecode($_GET['return_time']);
$user_id = urldecode($_GET['user_id']);

// Fetch user's name from the database
$sql = "SELECT fullname FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$user_name = "";

if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($user_name);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 900px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .book-heading {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #ff6600;
        }
        .book-image {
            width: 200px;
            height: 300px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            background: #f4f4f4;
            border-radius: 5px;
        }
        .book-details {
            margin-bottom: 20px;
            font-size: 1rem;
            color: #333;
        }
        .book-description {
            font-size: 1rem;
            margin-bottom: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="book-heading"><?php echo htmlspecialchars($book_title); ?></div>
        <img src="./images/thinkand.png" alt="Think and Grow Rich" class="book-image">
        <div class="book-details">
            <p><strong>User:</strong> <?php echo htmlspecialchars($user_name); ?></p>
            <p><strong>Author:</strong> Napoleon Hill</p>
            <p><strong>Return Date:</strong> <?php echo htmlspecialchars($return_date); ?></p>
            <p><strong>Return Time:</strong> <?php echo htmlspecialchars($return_time); ?></p>
        </div>
    </div>
</body>
</html>
