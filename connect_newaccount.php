<?php
session_start();

// جلب البيانات من الفورم
$f1 = $_POST['username'];
$f2 = $_POST['email'];
$f3 = $_POST['password'];
$f4 = $_POST['confirm_password'];

// التحقق من تطابق كلمة المرور
if ($f3 !== $f4) {
    die("❌ كلمة المرور وتأكيد كلمة المرور غير متطابقتين.");
}

// الاتصال بقاعدة البيانات
$conn = mysqli_connect('localhost', 'AiFelling', 'Yasmin225092', 'creataccount');
if (!$conn) {
    die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
}

// تشفير كلمة المرور
$hash = password_hash($f3, PASSWORD_DEFAULT);

// توليد كود عشوائي (OTP من 6 أرقام)
$code = rand(100000, 999999);

// إدخال بيانات المستخدم
$sql = "INSERT INTO accounts (names, emails, passwords, code) 
        VALUES ('$f1', '$f2', '$hash', '$code')";

if (mysqli_query($conn, $sql)) {
    // تخزين بيانات المستخدم في session لتسجيل الدخول التلقائي
    $_SESSION['username'] = $f1;
    $_SESSION['email']    = $f2;

    // تحويل لصفحة home مع رسالة نجاح
    header("Location: newhome.html?msg=success&username=" . urlencode($f1));
    exit;
} else {
    echo "خطأ: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
