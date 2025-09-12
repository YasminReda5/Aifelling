<?php
$conn=mysqli_connect('localhost','AiFelling','Yasmin225092','creataccount');

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