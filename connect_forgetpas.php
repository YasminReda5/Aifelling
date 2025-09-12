<?php
$f1=$_POST['Name'];
$f2=$_POST['Email'];
$f3=$_POST['Phone'];
$f4=$_POST['subject'];
$f5=$_POST['mass'];
$conn=mysqli_connect('localhost','AiFelling','Yasmin225092','contact_us');
mysqli_query($conn, "INSERT INTO  problem (names, emails, phones, subject ,massage ) VALUES ('$f1','$f2','$f3','$f4','$f5')");
// mysqli_query($conn, "INSERT INTO  problem (Name, Email, Phone, subject,mass)  VALUES ('ibra','yfffdasmi@gga445','121566662','snfhf','amdnf')");



?>
