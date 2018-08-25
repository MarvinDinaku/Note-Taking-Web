<!-- Button to trigger modal -->
<!-- Latest minified bootstrap css -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>

<!-- Latest minified bootstrap js -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Blog</title>
    <link rel="stylesheet" href="../style/normalize.css">
    <link rel="stylesheet" href="../style/main.css">
</head>
<body>


<div id="wrapper">

    <h1>My Notes
        <button style="margin-bottom: 3px; display: inline-block" class="btn btn-sm glyphicon glyphicon-plus"
         onclick="$('#modalForm').modal('show');"> </button>
        <a style='float: right;display: inline' href="../users/backend/logout.php">Logout</a></h1>

    <hr />

    <div id="myPost">
    </div>
    <?php

        //Here is where the session with the user Id starts
        session_start();

        //If session is started then run query to retrieve all notes of the logged user
        if (!isset($_SESSION['userId']))
        {
            header("Location:../../users/frontend/login_form.php");
        }else {
            $user_id = $_SESSION['userId'];
            require("../database/connection.php");

            $sql = "SELECT id,note_title, note_text, created_at FROM notes where user_id = '$user_id'  ORDER BY created_at DESC";
            $result = mysqli_query($conn, $sql);

            //Display all the notes of the user with styling
            if ($result == false) {
                echo(mysqli_error($conn));
            } else {

                while ($row = mysqli_fetch_assoc($result)) {

                    echo "<div id='".$row['id']."P' class='post-class'>";// Asign an id to the div so that deletion and edit is done using Ajax
                    echo '<h2>' . $row['note_title'] . '</a></h2>';
                    echo '<h4>Posted on ' . $row['created_at'] . '</h4>';
                    echo '<p>' . $row['note_text'] . '</p>';

                    echo " <div class='container'>
                          <div class='row'>
                       
                       
                       
                          <button id='".$row['id']."' class='btn btn-primary a-btn-slide-text edit_data'><span class='glyphicon glyphicon-edit' aria-hidden='true'></span>
                          <span><strong>Edit</strong></span></button>
                       
                        
                          <button id='".$row['id']."' class='btn btn-primary a-btn-slide-text delete_data'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span>
                          <span><strong>Delete</strong></span></button>
                       
                          </div>
                          </div>";
                    echo '<br/>';
                    echo '</div>';

                }

            }
        }

//    ?>

</div>


<!--Modal with the form to create new Notes-->

<div class="modal fade" id="modalForm" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Write a Note</h4>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <p class="statusMsg"></p>
                <form role="form">
                    <div class="form-group">
                        <label for="noteTitle">Title</label>
                        <input type="text" class="form-control" id="noteTitle" placeholder="Enter Title"/>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail">Note</label>
                        <textarea class="form-control" id="note" placeholder="Write your note"></textarea>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <input type="hidden" name="note_id" id="note_id" />
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary submitBtn" onclick="submitContactForm()">POST</button>
            </div>
        </div>
    </div>
</div>




<div class="modal fade" id="modalDelete" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Warning !</h4>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <p class="statusMsg"></p>
                <form role="form">
                    <div class="form-group">
                        <label for="noteTitle">Are you sure you want to delete note ?</label>

                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <input type="hidden" name="note_id" id="note_id" />
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary submitBtn">Yes</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>


<!--Script code for the modal to open and close regarding user selection.-->
<script>



    //Load Note to Modal when you try to edit it.
    $(document).on('click', '.edit_data', function(){

        var noteId = $(this).attr("id");

        $('#modalForm').modal('show');

        console.log(noteId);
            $.ajax({
                type:'POST',
                url:'load_note.php',
                data:{noteId:noteId},
                dataType:"json",
                success:function(data){

                    //If posting successful then append the new note without reloading page
                        $('#noteTitle').val(data.note_title);
                        $('#note').val(data.note_text);
                        $('#note_id').val(data.id);

                }
            });
        });


    //Ajax function to display modal and ask the user to delete the note or no

    $(document).on('click', '.delete_data', function(){

        var noteId = $(this).attr("id");

        $('#modalDelete').modal('show');


        console.log(noteId);
        $(document).on('click', '.submitBtn', function(){
            $('#modalDelete').modal('hide');

            $.ajax({
                type:'POST',
                url:'delete_note.php',
                data:{noteId:noteId},
                dataType:"json",

                success:function(data){

                    $( "#"+noteId+"P" ).remove(); //delete note with current note ID
                }
            });

        });

    });




    //Creation of the new note is done using AJAX
    function submitContactForm(){

        var noteTitle = $('#noteTitle').val();
        var note = $('#note').val();
        var note_id = $('#note_id').val();

        if(note.trim() == '' ){
            alert('Please enter a title.');
            $('#noteTitle').focus();
            return false;
        }else if(note.trim() == '' ){
            alert('Please write your Note.');
            $('#note').focus();
            return false;
        }else{
            //AJAX POST method to submit the input data to validation php file and then retrieve the response on JSON
            $.ajax({
                type:'POST',
                url:'notes_validation.php',
                data:'noteTitle='+noteTitle+'&note='+note+'&note_id='+note_id,
                dataType:'JSON',
                success:function(data){

                    //If posting successful then append the new note without reloading page

                    //var creationDate = JSON.parse(data);//Convert JSON to string and append it

                    $('#modalForm').modal('hide');


                    //If user wants to edit note than replace it with the new edited note
                    if($('#note_id').val() != '') {
                        console.log($('#note_id').val());
                        $("#"+data.note_id+"P").replaceWith("<div  class='post-class' id='"+data.note_id+'P'+"'><h2>" + noteTitle + "</a></h2><h4>Posted on " + data.created_at + "</h4> <p>" + note + "</p><div class='container'><div class='row'>" +
                            "<button  id='"+data.note_id+"' class='btn btn-primary a-btn-slide-text edit_data'><span class='glyphicon glyphicon-edit' aria-hidden='true'></span><span><strong>Edit</strong></span></button>   " +
                            "<button id='"+data.note_id+"' class='btn btn-primary a-btn-slide-text delete_data'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span><span><strong>Delete</strong></span></button>" +
                            "</div></div>" +
                            "</br></div>");

                        //Clear the modal
                        $('#noteTitle').val("");
                        $('#note').val("");
                        $('#note_id').val("");

                    }else{
                        //If user is creating a new node prepend it at the top of the notes

                        $('#myPost').prepend("<div  class='post-class' id='"+data.note_id+'P'+"'><h2>" + noteTitle + "</a></h2><h4>Posted on " + data.created_at + "</h4> <p>" + note + "</p><div class='container'><div class='row'>" +
                            "<button  id='"+data.note_id+"' class='btn btn-primary a-btn-slide-text edit_data'><span class='glyphicon glyphicon-edit' aria-hidden='true'></span><span><strong>Edit</strong></span></button>   " +
                            "<button id='"+data.note_id+"' class='btn btn-primary a-btn-slide-text delete_data'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span><span><strong>Delete</strong></span></button>" +
                            "</div></div>" +
                            "</br></div>");


                        //Clear the modal
                        $('#noteTitle').val("");
                        $('#note').val("");
                        $('#note_id').val("");

                    }

                }
            });
        }
    }
</script>

