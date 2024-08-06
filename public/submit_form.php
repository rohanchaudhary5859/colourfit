<?php
$servername = "localhost";
$username = "root";
$password = ""; // use your MySQL password if you have set one
$dbname = "contact_form_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $name = htmlspecialchars(strip_tags($_POST['name']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(strip_tags($_POST['phone']));
    $budget = htmlspecialchars(strip_tags($_POST['budget']));
    $website = htmlspecialchars(strip_tags($_POST['website']));
    $project = htmlspecialchars(strip_tags($_POST['project']));

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Prepare SQL statement to avoid SQL injection
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, phone, budget, website, project) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $phone, $budget, $website, $project);

        if ($stmt->execute()) {
            // Prepare email
            $to = "rohankumarchaudhry39@gmail.com"; // Replace with your email address
            $subject = "New Contact Form Submission";
            $message = "Name: $name\n";
            $message .= "Email: $email\n";
            $message .= "Phone: $phone\n";
            $message .= "Budget: $budget\n";
            $message .= "Website: $website\n";
            $message .= "Project Details:\n$project\n";

            $headers = "From: rohankumarchaudhry5859@gmail.com\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

            // Send email
            if (mail($to, $subject, $message, $headers)) {
                echo "Your message has been sent successfully!";
            } else {
                echo "There was an error sending your message.";
            }
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid email address.";
    }

    $conn->close();
}
?>
