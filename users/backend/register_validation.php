<?php

//Start session and validate all the fields coming from the front end
session_start();

$errors=0;

if(!isset($_POST['usr'])){
    ++$errors;
}else{
    $usr = $_POST['usr'];
}

if(!isset($_POST['first_name'])){
    ++$errors;
}else{
    $first_name = $_POST['first_name'];
}

if(!isset($_POST['last_name'])){
    ++$errors;
}else{
    $last_name = $_POST['last_name'];
}

if(!isset($_POST['email']))
{
    ++$errors;
}else{
    $email=$_POST['email'];
    if(preg_match("/^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/",$email)==0)
    {
        ++$errors;
    }

}

if(!isset($_POST['password']))
{
    ++$errors;
    echo ("<p>Password cannot be empty</p>");
}
else{
    $password=$_POST['password'];
    if(!isset($_POST['confirm_password']))
    {
        ++$errors;
    }
    else{
        $confirm_password=$_POST['confirm_password'];
        if(strlen($password)<6) {
			 ++$errors;
             echo json_encode('Short');
        }else{
        if($password<>$confirm_password) {
			 ++$errors;
            echo json_encode('Error');
        }
		}
    }
}



if($errors==0)
{
    require("../../database/connection.php");
    if($conn===false)
    {
        ++$errors;
        echo("Unable to connect to database");
    }
    else{

        $password_md5=md5($_POST['password']);
        $sql="select count(*) from users where email='$email'";
        $result=mysqli_query($conn, $sql);
        $row=mysqli_fetch_row($result);

        if($row[0]>0)
        {
			 ++$errors;
            echo json_encode('Exists');
        }
    }
    if($errors==0)
    {
        //Execute query and store the user if he/she does not exist
        $sql=" insert into users(username,first_name,last_name,email,password) 
		values('$usr','$first_name','$last_name','$email','$password_md5')";

        $result=mysqli_query($conn, $sql);
        if($result==false)
        {
echo json_encode('False');
        }else{
            $userid=mysqli_insert_id($conn);
            $_SESSION['userId'] = $userid;
			echo json_encode('Registered');
            //header("Location:../../notes/notes_form.php");
        }
    }
}