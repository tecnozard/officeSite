<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$host = 'tecnozard.com';
$dbname = 'tecnozar_office_site';
$username = 'tecnozar_office_site';
$password = 'kq36KARMSp';

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['message']);

    // Insert into database
    $sql = "INSERT INTO contacts (name, email, subject, message) 
            VALUES ('$name', '$email', '$subject', '$message')";

    if ($conn->query($sql) === TRUE) {
        // Send email via SMTP
        $mail = new PHPMailer(true);
        
        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host = 'mail.tecnozard.com';  
            $mail->SMTPAuth = true;
            $mail->Username = 'contact@tecnozard.com'; 
            $mail->Password = 'Contact@19';  
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;  

            
            $mail->setFrom('contact@tecnozard.com', 'Tecnozard');
            $mail->addAddress('contact@tecnozard.com');  
            $mail->addReplyTo($email, $name);  

            
            $mail->isHTML(true); 
            $mail->Subject = "New Contact Form Submission: $subject";
            $mail->Body    = "
            <h3>You have received a new message from the contact form:</h3>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Subject:</strong> $subject</p>
            <p><strong>Message:</strong></p>
            <p>$message</p>";

            // Send the email
            if ($mail->send()) {
                echo "Message sent and stored successfully!";
            } else {
                echo "Message stored, but email could not be sent.";
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
