<?php

$CC = $_GET["na"];
$to = "";

if($CC == ''){
    $to = "stefano.cecchettini1@gmail.com";
}else{
    $to = $CC;
}
    



// Define the subject of the email
$subject = "Test Email from PHP";

// Define the message body of the email
$message = "Hello, this is a test email sent from a PHP script!";

// Define the headers (e.g., From, Reply-To, etc.)
$headers = "From: no-reply@aironeaps.it" . "\r\n" .
           "Reply-To: info@aironeaps.it" . "\r\n" .
           "X-Mailer: PHP/" . phpversion();

// Use the mail() function to send the email
if(mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully!";
} else {
    echo "Failed to send email.";
}