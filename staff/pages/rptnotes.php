<?php

include("sessioncheck.php");
date_default_timezone_set('Asia/Kolkata');

$userid = $_SESSION['userid'];

if (isset($_POST['tabledata'])) {

    $data = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8mb4");
    $result = mysqli_query($connection, "SELECT n.*, s.name as subjectname, f.name as staffname 
                                        from notes as n inner join subject as s on n.subjectid=s.id 
                                        inner join staff f on n.staffid = f.id WHERE n.staffid = '$userid'");
    $data->list = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $result = mysqli_query($connection, "SELECT s.* from subject as s inner join mapping as m on s.id = m.subjectid WHERE s.status = '1' and m.staffid='$userid' and m.status = '1'");
    $data->subject = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo json_encode($data);
    exit();
}

//Update notes Status
if (isset($_POST['Update'])) {
    $msg = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8mb4");

    $id = mysqli_real_escape_string($connection, trim(strip_tags($_POST['userid'])));
    $status = mysqli_real_escape_string($connection, trim(strip_tags($_POST['status'])));
    $newstatus;

    if ($status == 1) {
        $newstatus = 0;
    } else {
        $newstatus = 1;
    }

    if (mysqli_query($connection, "Update notes set status = '$newstatus' where id = '$id'") <= 0) {
        $msg->value = 0;
        $msg->data = " You cannot update notes. Please Try again.";
        $msg->type = "alert alert-danger alert-dismissible ";
    } else {

        $msg->value = 1;
        $msg->data = "Notes Update Successfully.";
        $msg->type = "alert alert-success alert-dismissible ";
    }

    echo json_encode($msg);
    exit();
}

//Add Subject Notes 
if (isset($_POST['Add'])) {
    $msg = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8mb4");

    $staff = $_SESSION['userid'];
    $subject = mysqli_real_escape_string($connection, trim(strip_tags($_POST["subject"])));
    $class = mysqli_real_escape_string($connection, trim(strip_tags($_POST["class"])));

    $img = $_FILES['photo']['name'];
    $name = pathinfo($img, PATHINFO_FILENAME);
    $tmp = $_FILES['photo']['tmp_name'];
    $size = $_FILES['photo']['size'];
    // get uploaded file's extension
    $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
    $filename = $name . '_' . date("d-m-Y H-i-s") . '.' . $ext;
    move_uploaded_file($tmp, "../../notes/" . $filename);
    $date = date("d-m-Y H-i-s");

    if (mysqli_query($connection, "INSERT INTO `notes`(`staffid`, `subjectid`, `class`, `filename`, `type`, `size`, `status`, `date`)
                                    values ('$staff', '$subject','$class','$filename','$ext','$size','1' ,'$date')")) {

        $msg->value = 1;
        $msg->data = "Notes Added Successfully. ";
        $msg->type = "alert alert-success alert-dismissible ";
    } else {
        $msg->value = 0;
        $msg->data = "Please Check Information. or Try Again!";
        $msg->type = "alert alert-danger alert-dismissible ";
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
    <title><?= $project ?> : Notes Upload</title>
    <link rel="icon" href="../../dist/img/small.png" type="image/x-icon">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/adminLTE.min.css">
    <!-- adminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="../../bower_components/select2/dist/css/select2.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
        tfoot input {
            width: 50%;
            padding: 3px;
            box-sizing: border-box;
        }
    </style>
</head>

<body class="hold-transition <?= $skincolor ?> sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

        <?php include("header.php"); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h4>
                    <?= $project ?>
                    <small><?= $slogan ?></small>
                </h4>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Staff</a></li>
                    <li class="active">Notes</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Default box -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title"> Notes</h3>
                                <a class="btn btn-social-icon btn-primary pull-right" style="margin:5px" title="Add Notes" data-toggle="modal" data-target="#modaladdnotes"><i class="fa fa-plus"></i></a>
                            </div>
                            <div class="alert " id="alertclass" style="display: none">
                                <button type="button" class="close" onclick="$('#alertclass').hide()">×</button>
                                <p id="msg"></p>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <div class="box-body  table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class='text-center'>Id</th>
                                            <th class='text-center'>Staff Name</th>
                                            <th class='text-center'>Subject Name</th>
                                            <th class='text-center'>Class</th>
                                            <th class='text-center'>File Name</th>
                                            <th class='text-center'>File Type</th>
                                            <th class='text-center'>File Size</th>
                                            <th class='text-center'>Status</th>
                                            <th class='text-center'>Update</th>
                                            <th class='text-center'>Update</th>

                                        </tr>
                                    </thead>
                                    <tbody id="tbody">

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class='text-center'>Id</th>
                                            <th class='text-center'>Staff Name</th>
                                            <th class='text-center'>Subject Name</th>
                                            <th class='text-center'>Class</th>
                                            <th class='text-center'>File Name</th>
                                            <th class='text-center'>File Type</th>
                                            <th class='text-center'>File Size</th>
                                            <th class='text-center'>Status</th>
                                            <th class='text-center'>Update</th>
                                            <th class='text-center'>Update</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <!-- /.box-footer-->
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <!-- Add notes User modal -->
        <form id="addnotes" action="" method="post" enctype="multipart/form-data">
            <div class="modal fade" id="modaladdnotes" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-blue">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Add New Notes </h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert " id="addalertclass" style="display: none">
                                <button type="button" class="close" onclick="$('#addalertclass').hide()">×</button>
                                <p id="addmsg"></p>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Class </label>
                                <select class="form-control select2" style="width: 100%;" required name="class" id="class">
                                    <option value="">Select class</option>
                                    <option value="First Year">First Year</option>
                                    <option value="Second Year">Second Year</option>
                                    <option value="Third Year">Third Year</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Subject </label>
                                <select class="form-control select2 select3" style="width: 100%;" required name="subject" id="subject">
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Notes</label>
                                <input type="file" class="form-control" name="photo" id="photo" required>
                            </div>


                        </div>
                        <div class="modal-footer ">
                            <input type="hidden" name="Add" value="Add">
                            <button type="submit" name="Add" value="Add" id='add' class="btn btn-success" disabled>Upload Me</button>
                            <button type="button" class="btn pull-right btn-warning" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->

            </div>
        </form>
        <!-- End Add staff user modal -->

        <?php include("footer.php"); ?>

    </div>
    <!-- ./wrapper -->

    <!-- jQuery 3 -->
    <script src="../../bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="../../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="../../bower_components/fastclick/lib/fastclick.js"></script>
    <!-- adminLTE App -->
    <script src="../../dist/js/adminlte.min.js"></script>
    <!-- adminLTE for demo purposes -->
    <!-- <script src="../../dist/js/demo.js"></script> -->
    <!-- DataTables -->
    <script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- Select2 -->
    <script src="../../bower_components/select2/dist/js/select2.full.min.js"></script>
    <!-- QR code -->
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->
    <script type="text/javascript" src="../../dist/js/jquery-qrcode.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.sidebar-menu').tree()

            $('[data-toggle="tooltip"]').tooltip();

            $('#example1').DataTable({
                stateSave: true,
                destroy: true,
            });

            //Initialize Select2 Elements
            $('.select2').select2()

            //display data table   
            function tabledata() {
                $('.select3').empty();
                $('#example1').dataTable().fnDestroy();
                $('#example1 tbody').empty();
                $('#addnotes')[0].reset();

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'tabledata': 'tabledata'
                    },
                    success: function(response) {
                        //console.log(response);
                        var returnedData = JSON.parse(response);
                        var srno = 0;
                        $.each(returnedData['list'], function(key, value) {
                            srno++;
                            var update = "";
                            var viewtext = '<a class="btn btn-xs btn-primary" style= "margin:5px" title="Download" href= "../../notes/' + value.filename + '"><i class="fa fa-download"></i></a>';

                            if (value.status == 1) {
                                update = '<button type="submit" name="Update" id="Update" ' +
                                    'data-editid="' + value.id + '" data-status="' + value.status +
                                    '" class="btn btn-xs btn-danger update-button" style= "margin:5px" title="Deactivate" ><i class="fa fa-close"></i></button>';
                            } else {
                                update = '<button type="submit" name="Update" id="Update" ' +
                                    'data-editid="' + value.id + '" data-status="' + value.status +
                                    '" class="btn btn-xs btn-success update-button" style= "margin:5px" title="Activate" ><i class="fa fa-check"></i></button>';
                            }

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-center">' + srno + '</td>' +
                                '<td class="text-center">' + value.staffname + ' </td>' +
                                '<td class="text-center">' + value.subjectname + '</td>' +
                                '<td class="text-center">' + value.class + '</td>' +
                                '<td class="text-center">' + value.filename + '</td>' +
                                '<td class="text-center">' + value.type + '</td>' +
                                '<td class="text-center">' + (parseFloat(value.size) / 1024).toFixed(1) + 'KB</td>' +
                                '<td class="text-center">' + value.status + '</td>' +
                                '<td class="text-center">' + viewtext + update + '</td>' +
                                '<td class="text-center"><div class="id' + srno + '"></div></td>' +
                                '</tr>';                            
                            $('#example1 tbody').append(html);    
                            
                            alert($(location).attr('hostname')+'/notes/' + value.filename);
                            
                            $('.id'+srno).qrcode({
                                text: 'http://'+$(location).attr('hostname')+'/notes/' + value.filename,  
                                size: 100

                            });
                        });


                        $('#subject').append(new Option("Select subject", ""));
                        $.each(returnedData['subject'], function(key, value) {
                            $('#subject').append(new Option(value.name, value.id));
                        });

                        $('.select2').select2()

                        $('#example1').DataTable({
                            stateSave: true,
                            destroy: true,
                        });
                    }
                });
            }

            tabledata();

            $('#addnotes').submit(function(e) {

                $('#example1').dataTable().fnDestroy();
                $('#example1 tbody').empty();

                $('#addalertclass').removeClass();
                $('#addmsg').empty();

                e.preventDefault();

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: new FormData(this),
                    enctype: 'multipart/form-data',
                    processData: false, // tell jQuery not to process the data
                    contentType: false, // tell jQuery not to set contentType
                    success: function(response) {
                        // console.log(response);
                        returnedData = JSON.parse(response);
                        if (returnedData['value'] == 1) {
                            $('#addnotes')[0].reset();
                            $('#addalertclass').addClass(returnedData['type']);
                            $('#addmsg').append(returnedData['data']);
                            $("#addalertclass").show();
                            tabledata();
                        } else {
                            $('#addalertclass').addClass(returnedData['type']);
                            $('#addmsg').append(returnedData['data']);
                            $("#addalertclass").show();
                            tabledata();
                        }
                    }
                });

            });

            //Update notes status
            $(document).on("click", ".update-button", function(e) {

                $('#alertclass').removeClass();
                $('#msg').empty();

                e.preventDefault();
                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'Update': 'Update',
                        'userid': $(this).data('editid'),
                        'status': $(this).data('status')
                    },
                    success: function(response) {
                        //console.log(response);
                        returnedData = JSON.parse(response);
                        if (returnedData['value'] == 1) {
                            $('#alertclass').addClass(returnedData['type']);
                            $('#msg').append(returnedData['data']);
                            $("#alertclass").show();
                            tabledata();
                        } else {
                            $('#alertclass').addClass(returnedData['type']);
                            $('#msg').append(returnedData['data']);
                            $("#alertclass").show();
                        }
                        tabledata();
                    }
                });
            });

            $('#photo').on('change', function() {

                const size = (this.files[0].size / 1024 / 1024).toFixed(2);
                var extension = $("#photo").val().replace(/^.*\./, '');
                const allowed = ["jpg", "jpeg", "bmp", "png", "doc", "docx", "pptx", "ppt", "pdf", "mp4", "zip", "rar", "txt", "xls", "xlsx"];

                if (size > 10 || !(allowed.includes(extension))) {
                    alert("File must be less than 10 MB and allowed extentions only.");
                    $('#add').prop('disabled', true);

                } else {
                    $('#add').prop('disabled', false);
                }
            });
        })
    </script>
</body>

</html>