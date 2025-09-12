<?php
header('Content-Type: text/html; charset=utf-8');

$conn = new mysqli('localhost', 'AiFelling', 'Yasmin225092', 'creataccount');
if ($conn->connect_error) die("فشل الاتصال: " . $conn->connect_error);

$message = "";
$showForm = false;
$email = null;

// ✅ تحقق من الرابط (GET)
if(isset($_GET['emails'], $_GET['code'])){
    $email = $_GET['emails'];
    $code  = trim($_GET['code']); 

    $stmt = $conn->prepare("SELECT emails, code FROM accounts WHERE LOWER(emails)=LOWER(?) LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res && $res->num_rows > 0){
        $row = $res->fetch_assoc();
        $dbCode = trim($row['code']);

        if($dbCode === $code){
            $showForm = true;
        } else {
            $message = "الكود غير صحيح أو انتهت صلاحيته.";
        }
    } else {
        $message = "البريد الإلكتروني غير موجود.";
    }
    $stmt->close();
}

// ✅ تغيير كلمة المرور (POST)
if(isset($_POST['newPassword'])){
    // ناخد الايميل سواء من POST أو من الرابط
    $email    = $_POST['emails'] ?? ($_GET['emails'] ?? null);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if($password !== $confirm){
        $message = "كلمة المرور غير متطابقة.";
        $showForm = true;
    } else {
        // 🚫 بدون تشفير
        $upd = $conn->prepare("UPDATE accounts 
                               SET passwords=?, confirm_passwords=?, code=NULL 
                               WHERE emails=? LIMIT 1");
        $upd->bind_param("sss", $password, $confirm, $email);

        if($upd->execute() && $upd->affected_rows > 0){
            // ✅ بعد النجاح → تحويل لصفحة تسجيل الدخول
            header("Location: http://localhost/cns/logn.html?reset=success");
            exit;
        } else {
            $message = "فشل إعادة تعيين كلمة المرور (البيانات غير صحيحة).";
            $showForm = true;
        }
        $upd->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>إعادة تعيين كلمة المرور</title>
<link rel="stylesheet" href="resetcss.css">
</head>
<body>
<div class="reset-form">
    <h2>إعادة تعيين كلمة المرور</h2>
    <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>

    <?php if($showForm): ?>
    <form action="" method="post">
        <div>كلمة المرور الجديدة</div>
        <input type="password" name="password" required/><br><br>
        <div>تأكيد كلمة المرور</div>
        <input type="password" name="confirm_password" required/><br><br>

        <!-- ناخد الإيميل من الرابط أو المتغير -->
        <input type="hidden" name="emails" 
               value="<?php echo htmlspecialchars($_GET['emails'] ?? $email, ENT_QUOTES, 'UTF-8'); ?>"/>

        <button type="submit" name="newPassword">تعيين كلمة المرور</button>
    </form>
    <?php else: ?>
    <form action="mail.php" method="post">
        <div> البريد الإلكتروني</div>
        <input type="email" name="email" required/>
        <button type="submit" name="resetpassword">ارسال رابط اعادة تعيين كلمة المرور</button>
    </form>
    <?php endif; ?>
</div>
</body>
</html>
