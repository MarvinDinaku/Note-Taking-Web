
<!DOCTYPE html>
<html>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>

<!-- Latest minified bootstrap js -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style>

.modal-backdrop.in {
    filter: alpha(opacity=50);
    opacity: .5;
	display:none;
}

    body {font-family: Arial, Helvetica, sans-serif;}
    * {box-sizing: border-box;}

    /* Full-width input fields */
    input[type=text], input[type=password] {
        width: 100%;
        padding: 15px;
        margin: 5px 0 22px 0;
        display: inline-block;
        border: none;
        background: #f1f1f1;
    }

    /* Add a background color when the inputs get focus */
    input[type=text]:focus, input[type=password]:focus {
        background-color: #ddd;
        outline: none;
    }

    /* Set a style for all buttons */
    button {
        background-color: #4CAF50;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
        width: 100%;
        opacity: 0.9;
    }

    button:hover {
        opacity:1;
    }

    /* Extra styles for the cancel button */
    .cancelbtn {
        padding: 14px 20px;
        background-color: #f44336;
    }

    /* Float cancel and signup buttons and add an equal width */
    .cancelbtn, .signupbtn {
        float: left;
        width: 50%;
    }

    /* Add padding to container elements */
    .container {
        padding: 16px;
    }

    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
      opacity: 0.5;
    filter: alpha(opacity=50);
        padding-top: 50px;
    }

    /* Modal Content/Box */
    .modal-content {
        background-color: #fefefe;
        margin: 5% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
        border: 1px solid #888;
        width: 80%; /* Could be more or less, depending on screen size */
    }

    /* Style the horizontal ruler */
    hr {
        border: 1px solid #f1f1f1;
        margin-bottom: 25px;
    }

    /* The Close Button (x) */
    .close {
        position: absolute;
        right: 35px;
        top: 15px;
        font-size: 40px;
        font-weight: bold;
        color: #f1f1f1;
    }

    .close:hover,
    .close:focus {
        color: #f44336;
        cursor: pointer;
    }

    /* Clear floats */
    .clearfix::after {
        content: "";
        clear: both;
        display: table;
    }

    /* Change styles for cancel button and signup button on extra small screens */
    @media screen and (max-width: 300px) {
        .cancelbtn, .signupbtn {
            width: 100%;
        }
    }
</style>
<body>


<!--Login form with inputs-->
<div id="loginModal" class="modal-fade">
    <form class="modal-content" id="registerForm" name="registerForm">
        <div class="container">
            <h1>Login</h1>
            <p>Please fill in this form to Login</p>
            <hr>
            <label for="email"><b>Username</b></label>
            <input id="username" type="text" placeholder="Username" name="usr" required>

            <label for="password"><b>Password</b></label>
            <input id="password" type="password" placeholder="Enter Password" name="password" required>

            <label>
                <input type="checkbox" checked="checked" name="remember" style="margin-bottom:15px"> Remember me
            </label>

            <label style="margin-left: 50px">
                <a href="/Note Taking Web/users/frontend/register_form.php">Create Account</a>
            </label>

            <div class="clearfix">
                <button type="button" onclick="window.location.reload()" class="cancelbtn">Cancel</button>
                <button type="button" class="signupbtn" onclick="submitContactForm()">Login</button>
            </div>
        </div>
    </form>
</div>


<div class="modal fade" id="errorModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Login Failed</h4>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <p class="statusMsg"></p>
                <form role="form">
                    <div class="form-group">
                        <label for="noteTitle">Wrong Username of Password</label>

                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
            
            </div>
        </div>
    </div>
</div>

<script>
   

    // When the user clicks anywhere outside of the modal, close it


    $(window).on('load',function(){
        $('#loginModal').modal('show');
    });
	
	
	 function submitContactForm(){

        var username = $('#username').val();
        var password = $('#password').val();
       

        if(username.trim() == '' ){
            alert('Please enter a Username.');
            $('#username').focus();
            return false;
        }else if(password.trim() == '' ){
            alert('Please endter a password.');
            $('#password').focus();
            return false;
        }else{
            //AJAX POST method to submit the input data to validation php file and then retrieve the response on JSON
            $.ajax({
                type:'POST',
                url:'../backend/login_validation.php',
                data:'username='+username+'&password='+password,
                dataType:'JSON',
                success:function(data){

                    //If posting successful then append the new note without reloading page

                    if(data=="Error"){
						$('#errorModal').modal('show');
					}else if(data=="Yes"){
						window.location.replace("../../notes/notes_form.php");
					}
                  


                    //If user wants to edit note than replace it with the new edited note


                }
            });
        }
    }
	
	
</script>

</body>
</html>
