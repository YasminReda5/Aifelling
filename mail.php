<?php
require __DIR__ . '/vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$mail = new PHPMailer(true);
// require 'C:/xamp/htdocs/cns/PHPMailer-master/src/Exception.php';
// require 'C:/xamp/htdocs/cns/PHPMailer-master/src/PHPMailer.php';
// require 'C:/xamp/htdocs/cns/PHPMailer-master/src/SMTP.php';

$host = $_ENV['DB_HOST'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];
$db = $_ENV['DB_creataccount'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userEmail = trim($_POST['email']);

    $conn = new mysqli($host, $user, $pass, $db);
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
            $mail->Host = $_ENV['HOSTmail'];
            $mail->SMTPAuth = true;
            $mail->Port = $_ENV['portmail'];
            $mail->Username =$_ENV['mail'];
            $mail->Password = $_ENV['password'];

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
