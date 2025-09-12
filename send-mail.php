<?php
include('smtp/PHPMailerAutoload.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name   = $_POST['name'] ?? '';
    $email  = $_POST['email'] ?? '';
    $result = $_POST['result'] ?? '';

    if (!$name || !$email || !$result) {
        echo "error: missing fields";
        exit;
    }

    // User message
    $msgUser  = "<h2>Hello $name,</h2>";
    $msgUser .= "<p>Here is your personalized result:</p>";
    $msgUser .= "<p><strong>$result</strong></p>";

    // Admin message
    $msgAdmin  = "<h2>New Quiz Submission</h2>";
    $msgAdmin .= "<p><strong>Name:</strong> $name</p>";
    $msgAdmin .= "<p><strong>Email:</strong> $email</p>";
    $msgAdmin .= "<p><strong>Result:</strong> $result</p>";

    // Send to user
    $statusUser = smtp_mailer($email, "Your Personalized Practice Plan", $msgUser);

    // Send to admin
    $statusAdmin = smtp_mailer("spirit@haalchaalwitharzoo.com", "New Quiz Submission - $name", $msgAdmin);

    if ($statusUser === 'Delivered' && $statusAdmin === 'Delivered') {
        echo "success";
    } else {
        echo "error: mail not sent";
    }
} else {
    echo "error: invalid request";
}

// SMTP function
function smtp_mailer($recipient, $subject, $msg)
{
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Host = 'smtp.hostinger.com';
    $mail->Port = 587;
    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Username = 'spirit@haalchaalwitharzoo.com';
    $mail->Password = 'Arzoo@Haalchaal12';
    $mail->setFrom('spirit@haalchaalwitharzoo.com', 'Haalchaal With Arzoo');
    $mail->Subject = $subject;
    $mail->Body = $msg;

    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => false
        )
    );

    $mail->addAddress($recipient);

    if (!$mail->Send()) {
        return 'Failed';
    } else {
        return 'Delivered';
    }
}
