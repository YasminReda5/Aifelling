<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xamp/htdocs/cns/PHPMailer-master/src/Exception.php';
require 'C:/xamp/htdocs/cns/PHPMailer-master/src/PHPMailer.php';
require 'C:/xamp/htdocs/cns/PHPMailer-master/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = $_POST['Name'];
    $email   = $_POST['Email'];
    $phone   = $_POST['Phone'];
    $subject = $_POST['subject'];
    $message = $_POST['mass'];

    $mail = new PHPMailer(true);

    try {

        // إعدادات SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'redayasmin181@gmail.com'; // ايميلك
        $mail->Password = 'aesqfmvlahurtzay';        // App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = "UTF-8";      
        $mail->Encoding = "base64";

        // من - إلى
        $mail->setFrom($email, $name);
        $mail->addAddress('redayasmin181@gmail.com'); // ✨ ايميلك اللي هتستقبل عليه

        // المحتوى
        $mail->isHTML(true);
        $mail->Subject = "رسالة جديدة: $subject";
        $mail->Body    = "
            <strong>الاسم:</strong> $name <br>
            <strong>الإيميل:</strong> $email <br>
            <strong>الهاتف:</strong> $phone <br><br>
            <strong>الرسالة:</strong><br>$message
        ";

        $mail->send();
        echo "✅ تم إرسال الرسالة بنجاح!";
    } catch (Exception $e) {
        echo "❌ لم يتم الإرسال. الخطأ: {$mail->ErrorInfo}";
    }
}
?>
