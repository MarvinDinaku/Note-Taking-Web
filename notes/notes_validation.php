<?php
session_start();
require("../database/connection.php");
if(!empty($_POST)) {

    $title = $_POST['noteTitle'];
    $note = $_POST['note'];
    $user_id = $_SESSION['userId'];
    $date = date('Y-m-d h:i:s');

    if (!empty($_POST['note_id'])) {

        $noteId = $_POST['note_id'];

        if ($conn === false) {
            ++$errors;
            echo("Unable to connect to database");
        } else {

            $sql = "UPDATE notes SET note_title='$title',note_text='$note',created_at='$date' WHERE id= $noteId";

            $result = mysqli_query($conn, $sql);
            if ($result == false) {
                echo(mysqli_error($conn));
            } else {

                $response = array('note_id'=>$noteId,'created_at' => $date);

                echo json_encode($response);

            }
        }

    }else {

            if ($conn === false) {
                ++$errors;
                echo("Unable to connect to database");
            } else {


                $sql = " insert into notes(user_id,note_title,note_text,created_at)
		        values('$user_id','$title','$note','$date')";


                $sql2 = "select * from notes where note_title = '$title' and note_text = '$note'";

                $result = mysqli_query($conn, $sql);
                $result2 = mysqli_query($conn, $sql2);

                if ($result == false || $result2==false) {
                    echo(mysqli_error($conn));
                } else {

                    $row = mysqli_fetch_array($result2);

                    $noteId = $row['id'];

                    $response = array('note_id'=>$noteId,'created_at' => $date);

                    echo json_encode($response);

                }

            }

    }
}