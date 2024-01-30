<?php

include("sessioncheck.php");
date_default_timezone_set('Asia/Kolkata');

$userid = $_SESSION['userid'];

if (isset($_POST['tabledata'])) {

    $data = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8mb4");
    $result = mysqli_query($connection, "SELECT m.*, s.name as subjectname, f.name as staffname 
                                        from mapping as m inner join subject as s on m.subjectid=s.id 
                                        inner join staff f on m.staffid = f.id where m.staffid = '$userid' AND m.status = 1");
    $data->list = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo json_encode($data);
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $project ?> : Subject Allotment</title>
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
                        <li><a href="#">Admin</a></li>
                        <li class="active">Mapping</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Default box -->
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title"> Subject Allotment</h3>
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
                                            </tr>
                                        </thead>
                                        <tbody id="tbody">

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class='text-center'>Id</th>
                                                <th class='text-center'>Staff Name</th>
                                                <th class='text-center'>Subject Name</th>
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
    <script src="../../dist/js/demo.js"></script>
    <!-- DataTables -->
    <script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- Select2 -->
    <script src="../../bower_components/select2/dist/js/select2.full.min.js"></script>
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
                            var datatext = 'data-editid="' + value.id + '" data-status="' + value.status + '"';

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
                                '</tr>';
                            $('#example1 tbody').append(html);
                        });

                        $('#example1').DataTable({
                            stateSave: true,
                            destroy: true,
                        });
                    }
                });
            }

            tabledata();
        })
    </script>
</body>

</html>