<?php
if (isset($_POST['resetpassword'])) {
    $conn = new mysqli('localhost', 'AiFelling', '', 'creataccount');
    if ($conn->connect_error) {
        die("فشل الاتصال: " . $conn->connect_error);
    }

    $email = $_POST['email'];

    // تحقق إذا البريد موجود
    $stmt = $conn->prepare("SELECT emails FROM accounts WHERE emails = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // ✅ توليد كود عشوائي
        $token = bin2hex(random_bytes(16)); // 32 حرف عشوائي

        // ✅ تخزين الكود في العمود reset_token
        $update = $conn->prepare("UPDATE accounts SET reset_token = ? WHERE emails = ?");
        $update->bind_param("ss", $token, $email);
        $update->execute();

        echo "تم توليد الكود وحفظه في قاعدة البيانات: " . $token;

        // هنا ممكن تبعتي الكود للمستخدم عبر الإيميل
        // require_once 'mail.php';
        // $mail->addAddress($email);
        // $mail->Subject = "إعادة تعيين كلمة المرور";
        // $mail->Body = "الكود الخاص بك: $token";
        // $mail->send();

    } else {
        echo "البريد غير موجود!";
    }
}
?>
