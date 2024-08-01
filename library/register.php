<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'src/Exception.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_connect";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $confirm_password = $_POST['confirm-password'];

    // Check if password and confirm password match
    if ($_POST['password'] !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }

    $sql = "INSERT INTO users (fullname, contact, email, password) VALUES (?, ?, ?, ?)";

    // Prepare statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $fullname, $contact, $email, $password);

    if ($stmt->execute()) {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'meeshi0623@gmail.com';
            $mail->Password = 'abbg mfbt lzgc silg'; // Use the app password you generated
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            //Recipients
            $mail->setFrom('meeshi0623@gmail.com', 'LibraryConnect');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Registration Successful';
            $mail->Body    = "Dear $fullname,<br><br>Thank you for registering at LibraryConnect.<br>Your registration was successful. You can now log in using your credentials.<br><br>Regards,<br>LibraryConnect Team";

            $mail->send();
            header("Location: login.html"); // Redirect to login page after sending the email
            exit();
        } catch (Exception $e) {
            echo "Failed to send confirmation email. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
