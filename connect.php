<?php
$f1=$_POST['nameuser'];
$f2=$_POST['email'];
$f3=$_POST['password'];
$f4=$_POST['confirm_password'];
if ($f3 !== $f4) {
    die("كلمة المرور وتأكيد كلمة المرور غير متطابقتين.");
}
$conn=mysqli_connect('localhost','AiFelling','Yasmin225092','newaccount');

mysqli_query($conn, "INSERT INTO  accounts (names, emails, passwords, confirm_passwords) VALUES ('$f1','$f2','$f3','$f4')");
mysqli_query($conn, "INSERT INTO  accounts (names, emails, passwords, confirm_passwords) VALUES ('yasmin','$yasmi@gga445','1212','1212')");

?>
