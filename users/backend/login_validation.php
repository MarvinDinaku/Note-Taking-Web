<?php

require("../../database/connection.php");

//Start session with userId
session_start();

$usr=$_POST['username'];
$password=$_POST['password'];
$md5_password=md5($password);//Hash the password with MD5


//Run the query
$sql="select * from users where username='$usr' and password='$md5_password'";
$result=mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
//Check if the user already exists
if(mysqli_num_rows($result)==0)
{
    echo json_encode('Error');
}else{
        $userid=$row['id'];
        $_SESSION['userId'] = $userid;
        echo json_encode('Yes');
}
?>

