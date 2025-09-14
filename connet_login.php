<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$host = $_ENV['DB_HOST'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];
$db = $_ENV['DB_creataccount'];
$conn=mysqli_connect($host,$user,$pass,$db);
$email = $_POST['email'];
$password = $_POST['password'];

// 4. البحث عن المستخدم
$sql = "SELECT * FROM accounts WHERE emails = '$email'";
$result = $conn->query($sql);

// 5. التحقق من وجود المستخدم وكلمة السر
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    if ($password === $row['passwords']) {
          echo "
        <script>
            alert('تم تسجيل الدخول بنجاح');
            window.location.href = 'newhome.html';
        </script>";
    } else {
        echo "
        <script>
            alert('كلمة المرور غير صحيحة');
            window.history.back();
        </script>";
    }
} else {
    echo "
    <script>
        alert('المستخدم غير موجود');
        window.history.back();
    </script>";
}

$conn->close();
