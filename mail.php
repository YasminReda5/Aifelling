<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xamp/htdocs/cns/PHPMailer-master/src/Exception.php';
require 'C:/xamp/htdocs/cns/PHPMailer-master/src/PHPMailer.php';
require 'C:/xamp/htdocs/cns/PHPMailer-master/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userEmail = trim($_POST['email']);

    $conn = new mysqli('localhost', 'AiFelling', 'Yasmin225092', 'creataccount');
    if ($conn->connect_error) {
        die("فشل الاتصال: " . $conn->connect_error);
    }

    // تأكد أن الإيميل موجود
    $stmt = $conn->prepare("SELECT emails FROM accounts WHERE emails=?");
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        // توليد كود جديد
        $resetCode = bin2hex(random_bytes(16));

        // تحديث الكود في الجدول
        $upd = $conn->prepare("UPDATE accounts SET code=? WHERE emails=?");
        $upd->bind_param("ss", $resetCode, $userEmail);
        $upd->execute();

        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 2525;
            $mail->Username = 'ca2543ef1f0a4a';
            $mail->Password = 'ec210c52677254';

            $mail->setFrom('redayasmin181@gmail.com', 'اعادة تعيين كلمة المرور');
            $mail->addAddress($userEmail);

            // هنا غيرنا reset.php لـ resent.php
            $resetLink = "http://192.168.1.7/cns/resent.php?emails=$userEmail&code=$resetCode";

            $mail->isHTML(true);
            $mail->CharSet = "UTF-8";
            $mail->Encoding = "base64";
            $mail->Subject = "إعادة تعيين كلمة المرور";
            $mail->Body    = "اضغط هنا لتغيير كلمة المرور: <a href='$resetLink'>$resetLink</a>";

            $mail->send();
            echo "✅ تم إرسال رابط إعادة التعيين إلى $userEmail";
        } catch (Exception $e) {
            echo "خطأ في الإرسال: {$mail->ErrorInfo}";
        }
    } else {
        echo "❌ البريد غير موجود";
    }
}
?>
