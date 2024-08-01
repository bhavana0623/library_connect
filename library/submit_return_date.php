<?php
session_start(); // Start the session to access session variables

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

// Retrieve form data
$book_title = "Think and Grow Rich"; // This should be dynamically retrieved based on the book selected
$return_date = $_POST['return-date'];
$return_time = $_POST['return-time'];
$user_id = $_SESSION['user_id']; // Assuming the user ID is stored in the session

// Check if form data is not empty
if (!empty($return_date) && !empty($return_time) && !empty($user_id)) {
    // Insert data into the database
    $sql = "INSERT INTO book_returns (book_title, return_date, return_time, user_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssi", $book_title, $return_date, $return_time, $user_id);

        if ($stmt->execute()) {
            // Redirect to the summary page with all necessary details
            header("Location: summary.php?book_title=" . urlencode($book_title) . "&return_date=" . urlencode($return_date) . "&return_time=" . urlencode($return_time) . "&user_id=" . urlencode($user_id));
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "Return date, time, or user ID is missing.";
}

$conn->close();
?>
