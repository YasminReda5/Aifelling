<?php
session_start();

// لو مفيش سيشن يرجع لصفحة تسجيل الدخول
if (!isset($_SESSION['username'])) {
    header("Location: logn.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>الصفحة الرئيسية</title>
    <style>
        body { font-family: Arial, sans-serif; direction: rtl; text-align: right; }
        .msg {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php if (isset($_GET['msg']) && $_GET['msg'] === "success"): ?>
    <div class="msg">✅ تم تسجيل الدخول بنجاح</div>
<?php endif; ?>

<h1>مرحبًا يا <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</h1>
<p>أهلا بك في الصفحة الرئيسية</p>

</body>
</html>
