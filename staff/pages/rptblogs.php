<?php

include("sessioncheck.php");
date_default_timezone_set('Asia/Kolkata');

$userid = $_SESSION['userid'];


if (isset($_POST['tabledata'])) {

    $data = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8mb4");
    $result = mysqli_query($connection, "SELECT b.*, s.name as staffname from blogs as b inner join staff as s on b.staffid=s.id where b.staffid='$userid' and b.status='1'");
    $data->list = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo json_encode($data);
    exit();
}

//Update blog Status
if (isset($_POST['Update'])) {
    $msg = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8mb4");

    $userid = mysqli_real_escape_string($connection, trim(strip_tags($_POST['userid'])));
    $status = mysqli_real_escape_string($connection, trim(strip_tags($_POST['status'])));
    $newstatus;

    if ($status == 1) {
        $newstatus = 0;
    } else {
        $newstatus = 1;
    }

    if (mysqli_query($connection, "Update blogs set status = '$newstatus' where id = '$userid'") <= 0) {
        $msg->value = 0;
        $msg->data = " You cannot update information. Please Try again.";
        $msg->type = "alert alert-danger alert-dismissible ";
    } else {

        $msg->value = 1;
        $msg->data = "Blog Update Successfully.";
        $msg->type = "alert alert-success alert-dismissible ";
    }

    echo json_encode($msg);
    exit();
}

//Add blog 
if (isset($_POST['Add'])) {
    $msg = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8mb4");

    $title = mysqli_real_escape_string($connection, trim(strip_tags($_POST["title"])));
    $summary = mysqli_real_escape_string($connection, trim(strip_tags($_POST["summary"])));
    $information = mysqli_real_escape_string($connection, $_POST["information"]);


    if (mysqli_query($connection, "INSERT INTO `blogs`(`staffid`, `title`, `details`, `summary`, `status`) 
                                    values
                                    ('$userid', '$title','$information','$summary','1')")) {
        $msg->value = 1;
        $msg->data = "Blog Added Successfully.";
        $msg->type = "alert alert-success alert-dismissible";
    } else {
        $msg->value = 0;
        $msg->data = "Please Check Information.";
        $msg->type = "alert alert-danger alert-dismissible ";
    }

    echo json_encode($msg);
    exit();
}

//Edit information User 
if (isset($_POST['Edit'])) {
    $msg = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8mb4");

    $edittitle = mysqli_real_escape_string($connection, trim(strip_tags($_POST["edittitle"])));
    $editsummary = mysqli_real_escape_string($connection, trim(strip_tags($_POST["editsummary"])));
    $editinformation = mysqli_real_escape_string($connection, $_POST["editinformation"]);
    $editid = mysqli_real_escape_string($connection, trim(strip_tags($_POST["editid"])));

    if (mysqli_query($connection, "UPDATE `blogs` SET
                                    `title`='$edittitle', `details`='$editinformation', `summary`='$editsummary'                                    
                                    WHERE `id` = '$editid'") > 0) {
        $msg->value = 1;
        $msg->data = "Blog Update Successfully. ";
        $msg->type = "alert alert-success alert-dismissible ";
    } else {
        $msg->value = 0;
        $msg->data = "You cannot update blog. Please Try again.";
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
    <title><?= $project ?> : Blog Details </title>
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
    <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
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

<body class="hold-transition <?= $skincolor ?> layout-top-nav">
    <!-- Site wrapper -->
    <div class="wrapper">

        <?php include("header.php"); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="container">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h4>
                        <?= $project ?>
                        <small><?= $slogan ?></small>
                    </h4>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="#">Staff</a></li>
                        <li class="active">Blog</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Default box -->
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title"> Blog Details</h3>
                                    <a class="btn btn-social-icon btn-primary pull-right" style="margin:5px" title="Add Blog" data-toggle="modal" data-target="#modaladdinformation"><i class="fa fa-plus"></i></a>
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
                                                <th class='text-center'>Title</th>
                                                <th class='text-center'>Summary</th>
                                                <th class='text-center'>Status</th>
                                                <th class='text-center'>Update</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody">

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class='text-center'>Id</th>
                                                <th class='text-center'>Staff Name</th>
                                                <th class='text-center'>Title</th>
                                                <th class='text-center'>Summary</th>
                                                <th class='text-center'>Status</th>
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
        </div>
        <!-- Add information modal -->
        <!-- <form id="addinformation" action="" method="post"> -->
        <div class="modal fade" id="modaladdinformation" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Add Information</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert " id="addalertclass" style="display: none">
                            <button type="button" class="close" onclick="$('#addalertclass').hide()">×</button>
                            <p id="addmsg"></p>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Enter Title</label>
                            <input type="text" class="form-control" placeholder="Enter Title" name="title" id="title" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Summary</label>
                            <textarea class="form-control" rows="3" placeholder="Summary" name="summary" id="summary"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Information</label>
                            <textarea id="information" name="information" rows="10" cols="80" class='ckeditor'>
                                </textarea>
                        </div>

                    </div>
                    <div class="modal-footer ">
                        <input type="hidden" name="Add" value="Add">
                        <button type="submit" name="Add" value="Add" id='add' class="btn btn-success">Add Me</button>
                        <button type="button" class="btn pull-right btn-warning" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->

        </div>
        <!-- </form> -->
        <!-- End Edit information modal -->

        <!-- Edit information modal -->
        <!-- <form id="editinformation" action="" method="post"> -->
        <div class="modal fade" id="modaleditinformation" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-red">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Edit Information</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert " id="editalertclass" style="display: none">
                            <button type="button" class="close" onclick="$('#editalertclass').hide()">×</button>
                            <p id="editmsg"></p>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Enter Title</label>
                            <input type="text" class="form-control" placeholder="Enter Title" name="edittitle" id="edittitle" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Summary</label>
                            <textarea class="form-control" rows="3" placeholder="Summary" name="editsummary" id="editsummary"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Information</label>
                            <textarea id="editinformation" name="editinformation" rows="10" cols="80" class='ckeditor'>
                                </textarea>
                        </div>

                    </div>
                    <div class="modal-footer ">
                        <input type="hidden" name="editid" id='editid'>
                        <input type="hidden" name="Edit" value="Edit">
                        <button type="submit" name="Edit" value="Edit" id='Edit' class="btn btn-success">Edit Me</button>
                        <button type="button" class="btn pull-right btn-warning" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->

        </div>
        <!-- </form> -->
        <!-- End Edit information modal -->


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
    <!-- AdminLTE App -->
    <script src="../../dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../../dist/js/demo.js"></script>
    <!-- DataTables -->
    <script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- Select2 -->
    <script src="../../bower_components/select2/dist/js/select2.full.min.js"></script>
    <!-- CK Editor -->
    <script src="../../bower_components/ckeditor/ckeditor.js"></script>
    <script src=https://cdn.datatables.net/plug-ins/1.11.3/dataRender/ellipsis.js></script>
    <script>
        $(document).ready(function() {

            // CKEDITOR.replace('address')

            $('.sidebar-menu').tree()

            $('[data-toggle="tooltip"]').tooltip();

            $('#example1').DataTable({
                stateSave: true,
                destroy: true,
                columnDefs: [{
                    targets: '4',
                    render: $.fn.dataTable.render.ellipsis(10)
                }]
            });

            //Initialize Select2 Elements
            $('.select2').select2()


            //display data table   
            function tabledata() {

                $('#example1').dataTable().fnDestroy();
                $('#example1 tbody').empty();

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
                            var info = value.details;
                            var datatext = "data-editid='" + value.id + "' data-status='" + value.status +
                                "'data-title='" + value.title + "' data-details='" + info + "' data-summary='" + value.summary + "'";

                            var editbutton = '<button type="submit" name="Edit" id="edit" ' +
                                datatext +
                                ' class="btn btn-xs btn-warning edit-button" style= "margin:5px" title="Edit Blog" data-toggle="modal" data-target="#modaleditinformation"><i class="fa fa-edit"></i></button>';

                            if (value.status == 1) {
                                update = '<button type="submit" name="Update" id="Update" ' +
                                    'data-editid="' + value.id + '" data-status="' + value.status +
                                    '" class="btn btn-xs btn-danger update-button" style= "margin:5px" title="Deactivate Blog" ><i class="fa fa-close"></i></button>';
                            } else {
                                update = '<button type="submit" name="Update" id="Update" ' +
                                    'data-editid="' + value.id + '" data-status="' + value.status +
                                    '" class="btn btn-xs btn-success update-button" style= "margin:5px" title="Activate Blog" ><i class="fa fa-check"></i></button>';

                            }

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-center">' + srno + '</td>' +
                                '<td class="text-center">' + value.staffname + ' </td>' +
                                '<td class="text-center">' + value.title + '</td>' +
                                '<td class="text-center">' + value.summary + '</td>' +
                                '<td class="text-center">' + value.status + '</td>' +
                                '<td class="text-center">' + editbutton + update + '</td>' +
                                '</tr>';
                            $('#example1 tbody').append(html);

                        });

                        $('#example1').DataTable({
                            columnDefs: [{
                                targets: '4',
                                render: $.fn.dataTable.render.ellipsis(10)
                            }],
                            stateSave: true,
                            destroy: true,
                        });
                    }
                });
            }

            tabledata();

            // add information user
            $('#add').click(function(e) {
                // alert($('#title').val());

                $('#example1').dataTable().fnDestroy();
                $('#example1 tbody').empty();

                $('#addalertclass').removeClass();
                $('#addmsg').empty();

                // e.preventDefault();

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'title': $('#title').val(),
                        'information': CKEDITOR.instances.information.getData(),
                        'summary': $('#summary').val(),
                        'Add': 'Add'
                    },
                    success: function(response) {
                        console.log(response);
                        returnedData = JSON.parse(response);
                        if (returnedData['value'] == 1) {
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


            //Update information status
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
                        console.log(response);
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

            //Edit information  to modal
            $(document).on("click", ".edit-button", function(e) {
                $('#editalertclass').removeClass();
                $('#editmsg').empty();
                $(".modal-body #edittitle").val($(this).data('title'));
                $(".modal-body #editsummary").val($(this).data('summary'));
                CKEDITOR.instances['editinformation'].setData($(this).data('details'));
                $("#editid").val($(this).data('editid'));
            });

            //Edit information
            $('#Edit').click(function(e) {
                $('#editalertclass').removeClass();
                $('#editmsg').empty();
                e.preventDefault();

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'edittitle': $('#edittitle').val(),
                        'editinformation': CKEDITOR.instances.editinformation.getData(),
                        'editsummary': $('#editsummary').val(),
                        'Edit': 'Edit',
                        'editid': $('#editid').val()

                    },
                    success: function(response) {
                        console.log(response);
                        returnedData = JSON.parse(response);
                        if (returnedData['value'] == 1) {
                            $('#example1').dataTable().fnDestroy();
                            $('#example1 tbody').empty();

                            $('#editalertclass').addClass(returnedData['type']);
                            $('#editmsg').append(returnedData['data']);
                            $("#editalertclass").show();
                            tabledata();
                        } else {
                            $('#editalertclass').addClass(returnedData['type']);
                            $('#editmsg').append(returnedData['data']);
                            $("#editalertclass").show();
                        }
                    }
                });
            });
        })
    </script>
</body>

</html>