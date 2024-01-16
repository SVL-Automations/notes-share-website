<?php

include("../config.php");
include("../mail/mail.php");

session_start();
ob_start();

if (isset($_SESSION['VALID_ACADEMY_STUDENT'])) {
    header("location:pages/");
}

if (isset($_POST['tabledata'])) {

    $data = new \stdClass();

    $result = mysqli_query($connection, "SELECT * from department WHERE status = '1'");
    $data->department = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo json_encode($data);
    exit();
}


if (isset($_POST['Signup'])) {

    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $department = mysqli_real_escape_string($connection, $_POST['department']);
    $class = mysqli_real_escape_string($connection, $_POST['class']);
    $mobile = mysqli_real_escape_string($connection, $_POST['mobile']);

    $ptext = substr(str_shuffle("0123456789"), 0, 2) .
        substr(str_shuffle("abcdefghijkmnpqrstuvwxyz"), 0, 3) .
        substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ"), 0, 3);

    $encpassword = md5($ptext);

    $msg = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8");
    $result = mysqli_query($connection, "INSERT INTO `student`(`name`, `email`, `password`, `mobile`, `deptid`, `class`, `status`)
                                            VALUES('$name','$email','$encpassword','$mobile','$department','$class','1')");

    if ($result === true) {
        $msg->value = 1;
        $msg->type = "alert alert-success alert-dismissible ";
        $msg->data = "Registration completed successfully. Please check your email";

        $body =  "Dear " . $name . "  ,  <br/>

            Your new account is Created at " . $project . " as Student. 
            <br/> Welcome you to the <b>" . $project . "</b>.We thank you for join with us.<br/><br/>
        
            Your login ID is :" . $email . "<br/>
            Your Password is : " . $ptext . "<br/><br/>
            
            We request you to keep your login information confidential.<br/><br/>
            Thanks for Showing interest in our company.<br/><br/><br/>
            
            Regards,<br/>
            " . $project . "     
            ";

        $subject = "User Account Created at " . $project;
        $mailstatus = mailsend($email, $body, $subject, $project);
    } else {
        $msg->value = 0;
        $msg->type = "alert alert-danger alert-dismissible ";
        $msg->data = "Please check details or Email already exist.";
    }
    echo json_encode($msg);
    exit();
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $project ?></title>
    <link rel="icon" href="../dist/img/small.png" type="image/x-icon">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="../bower_components/select2/dist/css/select2.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

</head>

<body class="hold-transition login-page">
    <div class="login-box">

        <div class="login-logo">
            <a href="../"><b><?= $project ?> </b><br>User signup</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <div class="alert " id="alertclass" style="display: none">
                <button type="button" class="close" onclick="$('#alertclass').hide()">×</button>
                <p id="msg"></p>
            </div>
            <p class="login-box-msg">Sign up to start your session</p>

            <form action="" method="post" id="signupform">
                <div class="form-group has-feedback">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" class="form-control" placeholder="Enter name" name="name" required id="name" pattern="[a-zA-Z\s]+">
                </div>

                <div class="form-group has-feedback">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="email" class="form-control" placeholder="Enter email" name="email" required id="email">
                </div>

                <div class="form-group has-feedback">
                    <label for="exampleInputEmail1">Mobile</label>
                    <input type="number" class="form-control" placeholder="Enter Mobile" name="mobile" required id="mobile" min="2000000000" max="9999999999">
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword1">Department </label>
                    <select class="form-control select2 select3" style="width: 100%;" required name="department" id="department">
                    </select>
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword1">Class </label>
                    <select class="form-control select2" style="width: 100%;" required name="class" id="class">
                        <option value="">Select Department</option>
                        <option value="First Year">First Year</option>
                        <option value="Second Year">Second Year</option>
                        <option value="Third Year">Third Year</option>
                    </select>
                </div>

                <input type="hidden" name="Signup" value="Signup" id="Signup">
                <button type="submit" class="btn btn-lg btn-success btn-block " name="submit" id="submit">Sign Up</button>
                <a class="btn btn-lg btn-warning btn-block" href="index.php">Back</a>
            </form>
        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery 3 -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Select2 -->
    <script src="../bower_components/select2/dist/js/select2.full.min.js"></script>
    <script>
        $(document).ready(function() {

            $('.select2').select2();

            //display data table   
            function tabledata() {
                $('.select3').empty();

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'tabledata': 'tabledata'
                    },
                    success: function(response) {
                        //console.log(response);
                        var returnedData = JSON.parse(response);

                        $('.select3').append(new Option("Select department", ""));
                        $.each(returnedData['department'], function(key, value) {
                            $('.select3').append(new Option(value.name, value.id));
                        });

                        $('.select2').select2();
                    }
                });
            }

            tabledata();

            $('#signupform').submit(function(e) {

                $('#alertclass').removeClass();
                $('#msg').empty();

                e.preventDefault();

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: $('#signupform').serialize(),
                    success: function(response) {
                        console.log(response);

                        var returnedData = JSON.parse(response);

                        if (returnedData['value'] == 1) {
                            $('#alertclass').addClass(returnedData['type']);
                            $('#msg').append(returnedData['data']);
                            $("#alertclass").show();
                            $('#signupform')[0].reset();
                        } else {
                            $('#alertclass').addClass(returnedData['type']);
                            $('#msg').append(returnedData['data']);
                            $("#alertclass").show();
                        }
                    }
                });

            });
        });
    </script>
</body>

</html>