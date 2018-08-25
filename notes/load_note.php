<?php
   session_start();


    $noteId = $_POST['noteId'];

    $date = date('Y-m-d h:i:s');
    require("../database/connection.php");
    if ($conn === false) {
        ++$errors;
        echo("Unable to connect to database");
    } else {


        $sql = " select * from notes where id = $noteId";

        $result = mysqli_query($conn, $sql);

        if ($result == false) {
            echo(mysqli_error($conn));
        } else {

            $row = mysqli_fetch_array($result);


            echo json_encode($row);
        }

    }


?>